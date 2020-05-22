<?php


namespace mpyii\base;

use Yii;
use mpyii\base\BaseController;
use mpyii\libs\UserToken;
use myAdmin\models\Account;
use myAdmin\models\Tenant;
/**
 * Description of ManageController
 *
 * @author dong
 */
class ManageController extends BaseController {
    
    //登录后的用户信息
    public $adminUserInfo = array();
    
    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
    }
    
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        
        //验证用户信息
        if(!$this->userVerify()){
            mpOutPut('','NOT_LOGIN');
            return false;
        }
        
        return true;
    }
    
    private function userVerify()
    {
        $jwt = $this->_input['token'];
        $tenantToken = $this->_input['tenant_token'];
        if(!$jwt || !$tenantToken){
            return false;
        }
        
        //解析jwt
        try{
            $user = UserToken::UserTokenDecode('admin', $jwt);
        } catch (\Exception $ex) {
//            echo $ex->getMessage();exit;
            return false;
        }
        
        //???替换成redis
        //单点登录的验证
        //获取用户信息，如果用户信息里的登录时间和token里的登录时间不一致，则验证失败
        $res = Account::findWithCid()->andWhere(['id' => $user['user_id']])->one();
        if(!$res){
            return false;
        }
        if(!empty($user['login_time']) && $res['login_time'] != $user['login_time']){
            return false;
        }
        
        //解密平台租户信息，如果与当前登录用户的租户信息一致不用校验，如果不是需要验证令牌是否合规
        $tenantTokenArr = Tenant::TenantTokenDecode($tenantToken);
        if($tenantTokenArr['cid'] != $res['cid']){
            if($tenantToken != Tenant::TenantTokenEncode($res['cid'], $res->tenant['tenant_secret'], $tenantTokenArr['time'])){
                return false;
            }
        }
        
        $this->setAdminUserInfo($res);
        return $res;
    }
    
    /**
     * 设置用户登录后信息
     * @param type $res
     */
    private function setAdminUserInfo($res)
    {
        $this->adminUserInfo = $res;
    }
    
    public function globalTableKeys($opType = 'insert')
    {
        $params = array();
        
        //租户id，查出该用户支持权限的租户管理，如果前台传值cid，则判断是否在该权限范围内
        $this->adminUserInfo['cid'];
        
        $params['cid'] = isset($this->_input['cid'])?$this->_input['cid']:0;
        //溯源id   {处理ing}
        $params['ori_id'] = isset($this->_input['ori_id'])?$this->_input['ori_id']:0;
        //应用id   {处理ing}
        $params['module_id'] = isset($this->_input['module_id'])?$this->_input['module_id']:0;
        //来源id   {处理ing}
        $params['source_id'] = isset($this->_input['source_id'])?$this->_input['source_id']:0;
        //创建时间
        $params['create_time'] = time();
        //创建人
        $params['creator'] = isset($this->_input['creator'])?$this->_input['creator']:0;
        //更新时间
        $params['update_time'] = time();
        //更新人
        $params['updator'] = isset($this->_input['updator'])?$this->_input['updator']:0;
        //最后次操作ip
        $params['last_ip'] = Yii::$app->request->userIP;
        //状态
        $params['state'] = isset($this->_input['state'])?$this->_input['state']:0;
        //是否可删除  1表示可删除  0表示不可删除
        $params['can_delete'] = 1;
        //软删除   1表示已删除  0表示未删除
        $params['is_delete'] = isset($this->_input['is_delete'])?intval($this->_input['is_delete']):0;
        //排序1
//        $params['order_id'] = $this->_input['order_id'];
        //排序1
//        $params['order2_id'] = $this->_input['order2_id'];
        
        if($opType != 'insert'){
            unset($params['create_time'], $params['creator'], $params['cid'], $params['ori_id'], $params['module_id'], $params['source_id']);
        }
        
        $this->_input = array_merge($this->_input, $params);
        
        return $this->_input;
    }

}

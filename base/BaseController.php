<?php


namespace mpyii\base;

use Yii;
use yii\web\Controller;
/**
 * Description of ManageController
 *
 * @author dong
 */
class BaseController extends Controller {
    
//    //默认关闭csrf验证，解决post提交
    public $enableCsrfValidation = false;
    
    //提交的数据包含get，post
    public $_input = array();
    
    //租户id
    public $cId = 0;
    
    //模块id
    public $moduleId = 0;
    
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
        
        //初始化获取数据
        $this->initInput();
        
        return true;
    }
    
    //put your code here
    public function initInput()
    {
        $this->_input = array_merge(Yii::$app->request->get(),Yii::$app->request->post());
        
        //处理offset，limit，避免任意值过大
        $this->_input = mpOffsetLimitFormat($this->_input);

        
    }

}

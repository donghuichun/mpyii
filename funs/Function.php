<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\web\Response;

require __DIR__ . '/ParamsFormatFunc.php';
require __DIR__ . '/StrFunc.php';
require __DIR__ . '/FileFunc.php';

/**
 * 处理返回值null转换为空
 * @param string $data
 */
function mpReplaceNullToEmpty(&$data)
{
    if(is_array($data) && $data){
        foreach($data as $key=>$value){
            mpReplaceNullToEmpty($data[$key]);
        }
    }else if(is_null($data)){
        $data = '';
    }
}
/**
 * api输出
 * @param type $data
 * @param type $code
 * @param type $msg
 * @return array
 */
function mpOutPut($data = array(), $code = 'OK', $msg = '')
{
    //处理返回值为null置换为空
    mpReplaceNullToEmpty($data);
    
    if($code){
        $errorCode = require Yii::getAlias("@app") . '/config/errorCode.php';
        $moduleErrorCode = require Yii::getAlias("@app/modules/".Yii::$app->controller->module->id) . '/config/errorCode.php';
        $errorCode = $moduleErrorCode + $errorCode;
        $code = isset($errorCode[$code])? $errorCode[$code]: $code;
    }
    
    $ret['code'] = $code['code'];
    $ret['message'] = $msg? $msg: $code['msg'];
    $ret['data'] = $data;
    //$ret['csrfToken'] = Yii::$app->request->csrfToken;
    if(Yii::$app->request->getIsAjax()){
        Yii::$app->response->format = Response::FORMAT_JSONP;
        Yii::$app->response->data = ['data'=>$ret,'callback'=>'callback'];
    }else{
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = $ret; 
    }
    return Yii::$app->response->data;
    
}

/**
 * 常用目录
 */
function actionGetUrlList()
{
    echo "当前域名地址：".Yii::$app->request->hostInfo."<br>";
    echo "当前目录物理路径：".Yii::$app->basePath."<br>";  
    echo "当前项目路径：".dirname(Yii::$app->BasePath)."<br>";
    echo "当前Url: ".Yii::$app->request->url."<br>";
    echo "当前Home Url: ".Yii::$app->homeUrl."<br>";
    echo "当前return Url: ".Yii::$app->user->returnUrl."<br>";
    echo "获取当前模块ID方法：".Yii::$app->controller->module->id."<br>";
    echo "获取当前控制器的ID方法：".Yii::$app->controller->id."<br>";
    echo "获取当前action的ID方法：".Yii::$app->controller->action->id."<br>";
    echo "ip地址： ".Yii::$app->request->userIP."<br>";
    echo "上一个绝对Url:".Yii::$app->request->referrer."<br/>";
    echo "返回绝对路径:".Yii::$app->urlManager->createAbsoluteUrl('a/b')."<br/>";
    echo "是否启用路由美化".Yii::$app->urlManager->enablePrettyUrl."<br/>";
}



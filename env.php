<?php

/**
 * Setup application environment
 */
//$dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
//$dotenv->load();
//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->load();

defined('YII_DEBUG') or define('YII_DEBUG', mpgetenv('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', mpgetenv('YII_ENV') ?: 'prod');

//获取env变量，可根据模块进行获取，模块未配置的，取全局
function mpgetenv($varname, $modulename = '')
{
    $envArr = require(__DIR__ . '/../../../.env.php');
    if($modulename){
//        $mv = getenv($modulename.'_'.$varname);
        $mv = $envArr[$modulename.'_'.$varname];
        if($mv === NULL){
//            return getenv($varname);
            return $envArr[$varname];
        }
        return $mv;
    }else{
//        return getenv($varname);
        return $envArr[$varname];
    }
}

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
  * 获取随机字符串
  * @param int $randLength 长度
  * @param int $addtime 是否加入当前时间戳
  * @param int $includenumber 是否包含数字
  * @return string
  */
function mpRandStr($randLength = 6, $addtime = false, $includenumber = true)
{
     if ($includenumber) {
         $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
     } else {
         $chars = 'abcdefghijklmnopqrstuvwxyz';
     }
     $len = strlen($chars);
     $randStr = '';
     for ($i = 0; $i < $randLength; $i++) {
         $randStr .= $chars[mt_rand(0, $len - 1)];
     }
     $tokenvalue = $randStr;
     if ($addtime) {
         $tokenvalue = $randStr . time();
     }
     return $tokenvalue;
}

/**
 * 密码加密
 * @param type $pwd  密码字符串
 * @param type $salt  随机码
 * @param type $pwdIsEncry  密码是否经过了加密
 * @return type
 */
function mpPwdEncry($pwd, $salt, $pwdIsEncry = false)
{
    if($pwdIsEncry){
        return md5($pwd.$salt);
    }else{
        return md5(md5($pwd).$salt);
    }
}



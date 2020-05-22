<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * 分页参数的处理
 * @param type $data
 * @return array
 */
function mpOffsetLimitFormat($data)
{
    $data['offset'] = isset($data['offset'])?intval($data['offset']):0;
    $data['limit'] = isset($data['limit'])?intval($data['limit']):20;
    if($data['limit']>100)
        $data['limit'] = 100;
    return $data;
}

/**
 * 逗号隔开的字符串，检测是否数字
 * @param type $str
 * @return string
 */
function mpStrsCheckInt($str)
{
    if(!$str){
        return '';
    }
    $arr = explode(',', $str);
    foreach ($arr as $k=>$v){
        if((string)intval($v) !== (string)$v){
            unset($arr[$k]);
        }
    }
    return implode(',', $arr);
}

/**
 * 验证字符串至少8位，由大写字母、小写字母、数字组成
 * @param type $candidate
 * @return boolean
 */
function mpValidPass($candidate) {
    $r1='/[A-Z]/';  //uppercase
    $r2='/[a-z]/';  //lowercase
    $r3='/[0-9]/';  //numbers
    $r4='/[~!@#$%^&*()\-_=+{};:<,.>?]/';  // special char
 
    if(preg_match_all($r1,$candidate, $o)<1) {
        return FALSE;
    }
    if(preg_match_all($r2,$candidate, $o)<1) {
        return FALSE;
    }
    if(preg_match_all($r3,$candidate, $o)<1) {
        return FALSE;
    }
    //if(preg_match_all($r4,$candidate, $o)<1) {
    //    echo "密码必须包含至少一个特殊符号：[~!@#$%^&*()\-_=+{};:<,.>?]，请返回修改！<br />";
    //    return FALSE;
    //}
    if(strlen($candidate)<8) {
        return FALSE;
    }
    return TRUE;
} 
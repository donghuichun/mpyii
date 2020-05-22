<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 生成目录
 */
function mpCreateDir($str) {
    $arr = explode('/', $str);
    if (!empty($arr)) {
        $path = '';
        foreach ($arr as $k => $v) {
            $path .= $v . '/';
            if (!file_exists($path)) {
                mkdir($path, 0777);
                chmod($path, 0777);
            }
        }
    }
}

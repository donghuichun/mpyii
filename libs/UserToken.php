<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\core\lib;

use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenDecoded;
use Nowakowskir\JWT\TokenEncoded;
use Nowakowskir\JWT\Exceptions\IntegrityViolationException;
use Nowakowskir\JWT\Exceptions\TokenExpiredException;
use Nowakowskir\JWT\Exceptions\TokenInactiveException;
use Nowakowskir\JWT\Exceptions\Exception;

class UserToken{
    
    /**
     * 鉴权生成
     * @param type $iss 模块标识，需要在env文件里定义
     * @param array $data 传输数据
     * @return type
     */
    public static function UserTokenEncode($iss = 'admin', array $data)
    {
        $iss = strtoupper($iss);
        $key = mpgetenv('PLAT_SECRET');
        $header = ['typ' => 'JWT', 'alg' => JWT::ALGORITHM_HS256];
        $payload = [
            'iss'       => $iss, //jwt签发者，admin或者member
            'aud'       => '', //接收jwt的一方
            'exp'       => time()+intval(mpgetenv('TOKEN_EXP_TIME', $iss)), //jwt的过期时间，这个过期时间必须要大于签发时间
            'sub'       => '', //jwt所面向的用户,用户id
            'iat'       => time(), //jwt的签发时间
            'nbf'       => intval(mpgetenv('TOKEN_NBF_TIME', $iss)), //定义在什么时间之前，该jwt都是不可用的
            'data'      => $data,
            'jti'       => '', //jwt的唯一身份标识，主要用来作为一次性token,从而回避重放攻击。
            'tuk'       => mpgetenv('TOKEN_UNIQUE_KEY', $iss) //token唯一key，如果要之前失效，重设此值
        ];
        $tokenDecoded = new TokenDecoded($header, $payload);
        $tokenEncoded = $tokenDecoded->encode($key);

        $res = array();
        $res['token'] = $tokenEncoded->__toString();
        $res['payload'] = $payload;
        return $res;
    }
    
    /**
     * 鉴权验证
     * @param type $iss 模块标识，需要在env文件里定义
     * @param type $jwt 鉴权token
     * @return type
     * @throws \Exception
     */
    public static function UserTokenDecode($iss = 'admin', $jwt)
    {
        $iss = strtoupper($iss);
        $key = mpgetenv('PLAT_SECRET');
        $tokenEncoded = new TokenEncoded($jwt);
        try {
            $tokenEncoded->validate($key, JWT::ALGORITHM_HS256, intval(mpgetenv('TOKEN_LEEWAY_TIME', $iss)));
            $res = $tokenEncoded->decode()->getPayload();
            if($res['tuk'] != mpgetenv('TOKEN_UNIQUE_KEY', $iss)){
                throw new \Exception('token not equal unique key', 1);
            }
            return $res['data'];
        } catch (IntegrityViolationException $e) {
            throw new \Exception($e->getMessage(), 1);
        } catch (TokenExpiredException $e) {
            throw new \Exception($e->getMessage(), 1);
        }catch (TokenInactiveException $e) {
            throw new \Exception($e->getMessage(), 1);
        }catch (Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }
    
    
}

<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | Cookie设置
// +----------------------------------------------------------------------
return [
    // cookie 保存时间
    'expire' => 0,
    // cookie 保存路径
    'path' => '/',
    // cookie 有效域名
    'domain' => '',
    //  cookie 启用安全传输
    'secure' => false, //启用后 必须https
    //csrf跨域配置 开启后本地可以调试sessionid
//    'samesite' => 'None',
    // httponly设置
    'httponly' => false,
    // 是否使用 setcookie
    'setcookie' => true,
    // 跨域header
    'header' => [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'Form-Where, Authorization, Authori-zation, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, Sign, Kss-Device',
        'Access-Control-Allow-Methods' => 'GET,POST,PATCH,PUT,DELETE,OPTIONS,DELETE',
        'Access-Control-Max-Age' => '1728000',
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Expose-Headers' => 'Server'
    ],
    // 用户token名称
    'token_name' => 'Authorization',

];

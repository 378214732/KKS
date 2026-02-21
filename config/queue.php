<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
use think\facade\Env;

return [
    'default' => 'redis',
    'prefix' => Env::get('queue.prefix', 'kss_queue'),
    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],
        'database' => [
            'driver' => 'database',
            'queue' => 'default',
            'table' => 'jobs',
        ],
        'redis' => [
            'type' => 'redis',
            'queue' => Env::get('queue.listen_name', 'KSS_QUEUE'),
            'host' => Env::get('redis.redis_hostname', '127.0.0.1'),
            'port' => Env::get('redis.port', 6379),
            'password' => Env::get('redis.redis_password', ''),
            'select' => Env::get('redis.select', 0),
            'timeout' => 0,
            'persistent' => true,
            'retry_after' => 7200, // 添加这一行，设置为2小时间隔防止重复加入队列
        ],
    ],
    'failed' => [
        'type' => 'none',
        'table' => 'failed_jobs',
    ],
];

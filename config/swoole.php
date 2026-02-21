<?php

use Swoole\Table;

return strtoupper(substr(php_uname('s'), 0, 3)) === 'WIN' ? [] : [
    'http' => [
        'enable' => true,
        'host' => '0.0.0.0',
        'port' => 26335,
        'worker_num' => 4,
        'options' => [
            'package_max_length' => 50 * 1024 * 1024,
        ],
    ],
    'websocket' => [
        'enable' => true,
        'handler' => \kss\services\websocket\Manager::class,
        'ping_interval' => 25000,
        'ping_timeout' => 60000000,
        'room' => [
            'type' => 'table',
            'table' => [
                'room_rows' => 4096,
                'room_size' => 2048,
                'client_rows' => 8192,
                'client_size' => 2048,
            ],
            'redis' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'max_active' => 3,
                'max_wait_time' => 120,
            ],
        ],
        'listen' => [],
        'subscribe' => [],
    ],
    'rpc' => [
        'server' => [
            'enable' => false,
            'host' => '0.0.0.0',
            'port' => 9000,
            'worker_num' => swoole_cpu_num(),
            'services' => [
            ],
        ],
        'client' => [
        ],
    ],
    //队列
    'queue' => [
        'enable' => true,
        'workers' => [
            'KSS_QUEUE' => [
                'sleep' => 1,
                'worker_num' => swoole_cpu_num() < 8 ? 10 : 20,
            ],
            'KSS_QUEUE_BATCH' => [
                'sleep' => 1,
                'worker_num' => swoole_cpu_num() < 8 ? 10 : 20,
            ],
            'KSS_LOG' => [],
            'UPGRADE_CHECK' => [
                'sleep' => 1,
            ],
            'KSS_SYNC_ORDER' => [
                'sleep' => 1,
            ],
            'KSS_MONEY' => [
                'sleep' => 1,
            ],
            'KSS_NOTIFY' => [
                'sleep' => 1,
            ],
            'KSS_TASK' => [
                'sleep' => 1,
            ],
            'KSS_SYNC' => [
                'sleep' => 1,
                'worker_num' => swoole_cpu_num() < 8 ? 10 : 20,
            ],
        ],
    ],
    //热更新
    'hot_update' => [
        'enable' => false,
        'name' => ['*.php', '*.env'],
        'include' => [
            app_path(),
            root_path('route'),
            root_path('config'),
            root_path('kss'),
            root_path('vendor/kasushou'),
            root_path('addons'),
        ],
        'exclude' => [],
    ],
    //一键开启协程
    'coroutine' => [
        'enable' => true,
        'flags' => SWOOLE_HOOK_ALL,
    ],
    //连接池 每个进程都有一个连接池
    'pool' => [
        'db' => [
            'enable' => true,
            'max_active' => 500,
            'max_wait_time' => 120,
        ],
        'cache' => [
            'enable' => true,
            'max_active' => 10000,
            'max_wait_time' => 120,
        ],
        //自定义连接池
    ],
    'tables' => [//高性能内存数据库
        'user' => [
            'size' => 20480,
            'columns' => [
                ['name' => 'fd', 'type' => Table::TYPE_STRING, 'size' => 20],
                ['name' => 'type', 'type' => Table::TYPE_INT],
                ['name' => 'uid', 'type' => Table::TYPE_STRING, 'size' => 32],
                ['name' => 'to_uid', 'type' => Table::TYPE_STRING, 'size' => 32],
                ['name' => 'tourist', 'type' => Table::TYPE_INT],
                ['name' => 'user_where', 'type' => Table::TYPE_STRING, 'size' => 20],
            ]
        ]
    ],
    //每个worker里需要预加载以共用的实例
    'concretes' => [],
    //重置器
    'resetters' => [],
    //每次请求前需要清空的实例
    'instances' => [],
    //每次请求前需要重新执行的服务
    'services' => [],

];

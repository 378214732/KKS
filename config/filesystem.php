<?php

use think\facade\Env;

return [
    // 默认磁盘
    'default' => env('filesystem.driver', 'public'),
    // 磁盘列表
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'storage',
            'url'        => '/uploads',
        ],
        'public' => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/uploads',
            // 磁盘路径对应的外部URL路径
            'url'        => '/uploads',
            // 可见性
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
    ],
    //上传文件大小
    'filesize' => 50485760,
    //上传文件后缀类型
    'fileExt' => ['jpg', 'svg', 'jpeg', 'tar','ico', 'png', 'gif', 'pem', 'mp3', 'wma', 'wav', 'amr', 'mp4', 'key', 'xlsx', 'xls', 'video/mp4', 'zip', 'kss','crt','keystore'],
    //上传文件类型
    'fileMime' => [
        'image/jpeg',
        'image/x-icon',
        'image/gif',
        'image/svg+xml',
        'image/png',
        'image/vnd.microsoft.icon',
        'text/plain',
        'audio/mpeg',
        'application/octet-stream',
        'application/x-java-keystore',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-works',
        'application/vnd.ms-excel',
        'application/zip',
        'application/gzip',
        'text/xml',
    ],
];
<?php
// 项目入口文件
require __DIR__.'/vendor/autoload.php';
$app = new \think\App();
$app->run()->send();

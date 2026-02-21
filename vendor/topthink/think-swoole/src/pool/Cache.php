<?php

namespace think\swoole\pool;

use kss\util\Sys;
use think\App;
use think\swoole\pool\connector\CacheConnector;
use think\swoole\pool\proxy\Store;

class Cache extends \think\Cache
{

//
//    protected $cacheArr = [];
//
//    public function __construct(App $app)
//    {
//        $this->app = $app;
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], 'redis连接池对象创建');
//    }

    protected function createDriver(string $name)
    {

//        return new Store(function () use ($name) {
//            return parent::createDriver($name);
//        }, $this->app->config->get('swoole.pool.cache', []));

        //fonnie
        return new Store(new CacheConnector(function () use ($name) {
            return parent::createDriver($name);
        }), $this->app->config->get('swoole.pool.cache', []));
    }

//    public function __destruct()
//    {
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], 'redis连接池对象销毁');
//        // TODO: Implement __destruct() method.
//    }
}

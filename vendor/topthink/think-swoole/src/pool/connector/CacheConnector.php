<?php

namespace think\swoole\pool\connector;

use kss\util\Sys;
use Smf\ConnectionPool\Connectors\ConnectorInterface;
use think\swoole\pool\Connector;
use think\swoole\pool\Proxy;

class CacheConnector extends Connector
{
    protected $connector;

//    public function __construct($connector)
//    {
////        Sys::log([
////            '文件地址' => __FILE__ . ':' . __LINE__,
////        ], 'redis连接器创建');
//        $this->connector = $connector;
//    }

//    public function connect(array $config)
//    {
////        Sys::log([
////            '文件地址' => __FILE__ . ':' . __LINE__,
////        ], 'redis连接创建');
//        return call_user_func($this->connector, $config);
//    }


    public function disconnect($connection)
    {
        try {
//            clog(1, '缓存连接关闭');
//            Sys::log([
//                '文件地址' => __FILE__ . ':' . __LINE__,
//            ], 'redis连接关闭');

//            clog([
//                $connection
//            ], '缓存连接');
            $connection->close();
//            //redis创建器里的对象也清理
//            $this->connector = null;
        } catch (\Throwable $e) {
//            clog($e, '缓存连接关闭错误');
//            Sys::log([
//                '文件地址' => __FILE__ . ':' . __LINE__,
//                '错误信息' => $e->getMessage(),
//            ], 'redis连接关闭错误');
        }
    }

    public function isConnected($connection): bool
    {
//        clog(!property_exists($connection, Proxy::KEY_DISCONNECTED), '缓存报错关闭');
        return !property_exists($connection, Proxy::KEY_DISCONNECTED);
    }

    public function reset($connection, array $config)
    {
//        clog(1, '缓存重置');
    }

    public function validate($connection): bool
    {
//        clog(1, '缓存验证');
        return true;
    }

//    public function __destruct()
//    {
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], 'redis连接器销毁');
//    }
}

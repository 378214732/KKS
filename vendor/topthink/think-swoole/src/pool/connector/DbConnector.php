<?php

namespace think\swoole\pool\connector;

use kss\util\Sys;
use Smf\ConnectionPool\Connectors\ConnectorInterface;
use think\db\ConnectionInterface;
use think\swoole\pool\Connector;
use think\swoole\pool\Proxy;

class DbConnector extends Connector
{
    protected $connector;

    public function __construct($connector)
    {
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '数据库连接器创建');
        $this->connector = $connector;
    }

    public function connect(array $config)
    {
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '数据库连接创建');
        return call_user_func($this->connector, $config);
//        return $this->connector->connect($config);
    }

    public function disconnect($connection)
    {
        try {
//            clog(1, '数据库连接关闭');

//            Sys::log([
//                '文件地址' => __FILE__ . ':' . __LINE__,
//            ], '数据库连接关闭');
//            $connection->close()->clear();
            $connection->close();

//            $connection->getPdo()->close();
//            $this->connector = null;
        } catch (\Throwable $e) {
//            Sys::log([
//                '文件地址' => __FILE__ . ':' . __LINE__,
//                '错误信息' => $e->getMessage(),
//            ], '数据库连接关闭错误');
//            clog($e->getMessage(), '数据库连接关闭');
        }
    }

    public function isConnected($connection): bool
    {
//        clog(!property_exists($connection, Proxy::KEY_DISCONNECTED), '数据库报错关闭');
        return !property_exists($connection, Proxy::KEY_DISCONNECTED);
    }

    public function reset($connection, array $config)
    {
//        clog(1, '数据库连接重置');
    }

    public function validate($connection): bool
    {
//        clog(1, '数据库连接验证');
        return true;
    }

//    public function __destruct()
//    {
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '数据库连接器销毁');
//    }
}

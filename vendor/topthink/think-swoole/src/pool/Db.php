<?php

namespace think\swoole\pool;

use kss\util\Sys;
use think\Config;
use think\db\ConnectionInterface;
use think\Log;
use think\Model;
use think\swoole\pool\connector\DbConnector;
use think\swoole\pool\proxy\Connection;

/**
 * Class Db
 * @package think\swoole\pool
 * @property Config $config
 */
class Db extends \think\Db
{

//    protected $dbArr = [];

//    public function __construct()
//    {
//        parent::__construct();
//
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '数据库连接池对象创建');
//    }

//    /**
//     * 创建/切换数据库连接查询
//     * @access public
//     * @param string|null $name 连接配置标识
//     * @param bool $force 强制重新连接
//     * @return ConnectionInterface
//     */
//    public function connect(string $name = null, bool $force = false)
//    {
//
//        clog($name, '数据库连接池对象连接');
//
//        return $this->createConnection((string)$name);
//    }


    protected function createConnection(string $name): ConnectionInterface
    {
//        return new Connection(new class(function () use ($name) {
//            return parent::createConnection($name);
//        }) extends Connector {
//            public function disconnect($connection)
//            {
//                if ($connection instanceof ConnectionInterface) {
//                    $connection->close();
//                }
//            }
//        }, $this->config->get('swoole.pool.db', []));
//        return parent::createConnection($name);

        //fonnie
        return new Connection(new DbConnector(function () use ($name) {

//            Sys::log([
//                '文件地址' => __FILE__ . ':' . __LINE__,
//            ], '数据库连接池对象创建连接');
            return parent::createConnection($name);

//            $connection = parent::createConnection($name);
//            $connection->md5 = bin2hex(random_bytes(16));
//            return $connection;
        }), $this->config->get('swoole.pool.db', []));
    }


    protected function getConnectionConfig(string $name): array
    {
        $config = parent::getConnectionConfig($name);

//        //打开断线重连
//        $config['break_reconnect'] = true;
        return $config;
    }

//    public function __destruct()
//    {
//        parent::__destruct();
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '数据库连接池对象销毁');
//        // TODO: Implement __destruct() method.
//    }
}

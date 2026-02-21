<?php

namespace think\swoole\pool;

use Closure;
use Exception;
use kss\util\Sys;
use RuntimeException;
use Smf\ConnectionPool\ConnectionPool;
use Smf\ConnectionPool\Connectors\ConnectorInterface;
use Swoole\Coroutine;
use think\swoole\coroutine\Context;
use think\swoole\Pool;
use Throwable;

#[\AllowDynamicProperties]
abstract class Proxy
{
    const KEY_RELEASED = '__released';

    const KEY_DISCONNECTED = '__disconnected';

    protected $pool;

    /**
     * Proxy constructor.
     * @param Closure|ConnectorInterface $connector
     * @param array $config
     */
    public function __construct($connector, $config, array $connectionConfig = [])
    {
//        if ($connector instanceof Closure) {
//            $connector = new Connector($connector);
//        }

        //fonnie
        if ($connector instanceof Connector) {
//            clog($config,'链接');
        } else if ($connector instanceof Closure) {
            $connector = new Connector($connector);
        }


        $this->pool = new ConnectionPool(
            Pool::pullPoolConfig($config),
            $connector,
            $connectionConfig
        );

//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '代理对象创建');
//        cloge($config,'链接配置');


        //初始化连接池
        $this->pool->init();

    }

    protected function getPoolConnection()
    {
        $key = 'connection.' . spl_object_id($this);

//        clog($key, '链接键');
        return Context::rememberData($key, function () {
            $connection = $this->pool->borrow();

            $connection->{static::KEY_RELEASED} = false;

            Coroutine::defer(function () use ($connection) {
                //自动释放
                $this->releaseConnection($connection);
            });

            return $connection;
        });
    }

    /**
     * 将链接返回到连接池
     */
    protected function releaseConnection($connection)
    {
        if ($connection->{static::KEY_RELEASED}) {
            return;
        }
        $connection->{static::KEY_RELEASED} = true;
        $this->pool->return($connection);
    }

    public function release()
    {
        $connection = $this->getPoolConnection();
        $this->releaseConnection($connection);
    }

    public function __call($method, $arguments)
    {
        $connection = $this->getPoolConnection();
        if ($connection->{static::KEY_RELEASED}) {
            throw new RuntimeException('Connection already has been released!');
        }

        try {
            return $connection->{$method}(...$arguments);
        } catch (Exception|Throwable $e) {
            //sql事务报错无需关闭连接
            if ($method !== 'transaction') {
//                file_put_contents(runtime_path() . 'clog_proxy_error.json', $e->getMessage() . "\r\n\r\n", 8);
                //sql语句不断开链接
                if (stripos($e->getMessage(), 'SQLSTATE') === 0) {
//                    clog([
//                        $e->getMessage(),
//                    ], '链接发生错误');
                } else {
                    $connection->{static::KEY_DISCONNECTED} = true;
                }
            }
            throw $e;
        }
    }

//    public function __destruct()
//    {
//        //清空连接池
////        $this->pool = null;
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '代理对象销毁');
//    }

}

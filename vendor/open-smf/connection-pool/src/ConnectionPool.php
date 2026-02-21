<?php

namespace Smf\ConnectionPool;

use kss\util\SafeChannel;
use kss\util\Sys;
use Smf\ConnectionPool\Connectors\ConnectorInterface;
use Swoole\Coroutine\Channel;
use Swoole\Coroutine;

class ConnectionPool implements ConnectionPoolInterface
{
    /**@var float The timeout of the operation channel */
    const CHANNEL_TIMEOUT = 0.001;

    /**@var float The minimum interval to check the idle connections */
    const MIN_CHECK_IDLE_INTERVAL = 10;

    /**@var string The key about the last active time of connection */
    const KEY_LAST_ACTIVE_TIME = '__lat';

    /**@var bool Whether the connection pool is initialized */
    protected $initialized;

    /**@var bool Whether the connection pool is closed */
    protected $closed;

    /**@var Channel The connection pool */
    protected $pool;

    /**@var ConnectorInterface The connector */
    protected $connector;

    /**@var array The config of connection */
    protected $connectionConfig;

    /**@var int Current all connection count */
    protected $connectionCount = 0;

    /**@var int The minimum number of active connections */
    protected $minActive = 1;

    /**@var int The maximum number of active connections */
    protected $maxActive = 1;

    /**@var float The maximum waiting time for connection, when reached, an exception will be thrown */
    protected $maxWaitTime = 5;

    /**@var float The maximum idle time for the connection, when reached, the connection will be removed from pool, and keep the least $minActive connections in the pool */
    protected $maxIdleTime = 5;

    /**@var float The interval to check idle connection */
    protected $idleCheckInterval = 5;

    /**@var int The timer id of balancer */
    protected $balancerTimerId;

    /**
     * ConnectionPool constructor.
     * @param array $poolConfig The minimum number of active connections, the detail keys:
     * int minActive The minimum number of active connections
     * int maxActive The maximum number of active connections
     * float maxWaitTime The maximum waiting time for connection, when reached, an exception will be thrown
     * float maxIdleTime The maximum idle time for the connection, when reached, the connection will be removed from pool, and keep the least $minActive connections in the pool
     * float idleCheckInterval The interval to check idle connection
     * @param ConnectorInterface $connector The connector instance of ConnectorInterface
     * @param array $connectionConfig The config of connection
     */
    public function __construct(array $poolConfig, ConnectorInterface $connector, array $connectionConfig)
    {
        $this->initialized = false;
        $this->closed = false;
        $this->minActive = $poolConfig['minActive'] ?? 20;
        $this->maxActive = $poolConfig['maxActive'] ?? 100;
        $this->maxWaitTime = $poolConfig['maxWaitTime'] ?? 5;
        $this->maxIdleTime = $poolConfig['maxIdleTime'] ?? 30;

        $poolConfig['idleCheckInterval'] = $poolConfig['idleCheckInterval'] ?? 15;
        $this->idleCheckInterval = $poolConfig['idleCheckInterval'] >= static::MIN_CHECK_IDLE_INTERVAL ? $poolConfig['idleCheckInterval'] : static::MIN_CHECK_IDLE_INTERVAL;
//        $this->maxIdleTime = 5;//超时时间
//        $this->idleCheckInterval = 2;//监测时间

        $this->connectionConfig = $connectionConfig;
        $this->connector = $connector;
    }


    /**
     * Initialize the connection pool
     * @return bool
     */
    public function init(): bool
    {
        if ($this->initialized) {
            return false;
        }
        $this->initialized = true;
//        $this->pool = new Channel($this->maxActive);
        $this->pool = new SafeChannel($this->maxActive);
        $this->balancerTimerId = $this->startBalanceTimer($this->idleCheckInterval);

//        Sys::log([
//        cloge([
//            '文件地址' => __FILE__ . ':' . __LINE__,
////            '最小链接数' => $this->minActive,//默认0
//            '最大链接数' => $this->maxActive,
////            '定时检测链接关闭' => $this->idleCheckInterval,
////            '当前链接信息' => $this->connectionConfig,
//        ], '初始化连接池');

        //先别初始化省的浪费连接
        Coroutine::create(function () {
            try {
                for ($i = 0; $i < $this->minActive; $i++) {
                    $connection = $this->createConnection();

//                    if ($this->minActive == 500) {
//                        Sys::log([
//                            '文件地址' => __FILE__ . ':' . __LINE__,
////                            '创建连接' => $connection->md5,
//                        ]);
//                    }

                    $ret = $this->pool->push($connection, static::CHANNEL_TIMEOUT);
                    if ($ret === false) {
                        $this->removeConnection($connection);
                    }
                }
            } catch (\Throwable $e) {
                Sys::log([
                    '文件地址' => __FILE__ . ':' . __LINE__,
                    '创建链接失败' => $e->getMessage(),
                ]);
            }
        });
        return true;
    }


    /**
     * Borrow a connection from the connection pool, throw an exception if timeout
     * @return mixed The connection resource
     * @throws BorrowConnectionTimeoutException
     * @throws \RuntimeException
     */
    public function borrow()
    {
        if (!$this->initialized) {
            throw new \RuntimeException('Please initialize the connection pool first, call $pool->init().');
        }
//        if ($this->maxActive == 500) {
//            dump($this->connectionCount);
//        }
        if ($this->pool->isEmpty()) {
            // Create more connections
            if ($this->connectionCount < $this->maxActive) {

//                if ($this->maxActive == 500) {
//                    try {
//                        return $this->createConnection();
//                    } catch (\Throwable $e) {
//                        clog($e->getMessage(), '测试彩色');
//                        //连接池不够用了
//                        if (stripos($e->getMessage(), 'Too many connections') !== false) {
//                            $tick = 60;
//                            //创建数据库连接失败就等待60秒内是否有空连接
//                            for ($i = 0; $i < $tick; $i++) {
//                                clog($tick, '等待连接池放入连接');
//                                $connection = $this->pool->pop(1);
//                                if ($connection) {
//                                    return $connection;
//                                }
//                            }
//                        }
//
//                        throw $e;
//                    }
//                }

                if (empty($connection)) {
                    return $this->createConnection();
                }
            }
        }

        $connection = $this->pool->pop($this->maxWaitTime);
        if ($connection === false) {
            $exception = new BorrowConnectionTimeoutException(sprintf(
                'Borrow the connection timeout in %.2f(s), connections in pool: %d, all connections: %d',
                $this->maxWaitTime,
                $this->pool->length(),
                $this->connectionCount
            ));
            $exception->setTimeout($this->maxWaitTime);
            throw $exception;
        }
        if ($this->connector->isConnected($connection)) {
            // Reset the connection for the connected connection
            $this->connector->reset($connection, $this->connectionConfig);
//            //数据库有断线重连继续放回队列
//            if ($this->maxActive == 500) {
//                //数据库将连接继续放回队列
//                $this->return($connection);
//            }
        } else {
//            if ($this->maxActive == 500) {
//                clog([
//                    '最小活动连接数' => $this->minActive,
//                    '最大活动连接数' => $this->maxActive,
//                    '当前连接数' => $this->connectionCount,//当前连接数
//                    '超时时间' => $this->maxIdleTime,
//                    '连接唯一标识' => $connection->md5 ?? '',
//                ], '无效连接先关闭再创建');
//            }
            // Remove the disconnected connection, then create a new connection
            $this->removeConnection($connection);
            $connection = $this->createConnection();
        }
        return $connection;
    }

    /**
     * Return a connection to the connection pool
     * @param mixed $connection The connection resource
     * @return bool
     */
    public function return($connection): bool
    {
        if (!$this->connector->validate($connection)) {
            throw new \RuntimeException('Connection of unexpected type returned.');
        }

        if (!$this->initialized) {
            throw new \RuntimeException('Please initialize the connection pool first, call $pool->init().');
        }
        if ($this->pool->isFull()) {
//            clog(1, '连接池满了');
            // Discard the connection
            $this->removeConnection($connection);
            return false;
        }

//        if ($this->maxActive == 500) {
//            clog([
//                '最小活动连接数' => $this->minActive,
//                '最大活动连接数' => $this->maxActive,
//                '当前连接数' => $this->connectionCount,//当前连接数
//                '超时时间' => $this->maxIdleTime,
//                '连接唯一标识' => $connection->md5 ?? '',
//            ], '连接池返回连接');
//
//        }


        $connection->{static::KEY_LAST_ACTIVE_TIME} = time();
        $ret = $this->pool->push($connection, static::CHANNEL_TIMEOUT);
        if ($ret === false) {
            $this->removeConnection($connection);
        }
        return true;
    }

    /**
     * Get the number of created connections
     * @return int
     */
    public function getConnectionCount(): int
    {
        return $this->connectionCount;
    }

    /**
     * Get the number of idle connections
     * @return int
     */
    public function getIdleCount(): int
    {
        return $this->pool->length();
    }

    /**
     * Close the connection pool and disconnect all connections
     * @return bool
     */
    public function close(): bool
    {
        if (!$this->initialized) {
            return false;
        }
        if ($this->closed) {
            return false;
        }
        $this->closed = true;
        swoole_timer_clear($this->balancerTimerId);
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//            '关闭连接池' => $this->pool->length(),
//        ]);
        Coroutine::create(function () {
            while (true) {
                if ($this->pool->isEmpty()) {
                    break;
                }
                $connection = $this->pool->pop(static::CHANNEL_TIMEOUT);
                if ($connection !== false) {
                    $this->connector->disconnect($connection);
                }
            }
            $this->pool->close();
//            Sys::log([
//                '文件地址' => __FILE__ . ':' . __LINE__,
//                '关闭连接池' => $this->pool->length(),
//            ]);
//            unset($this->pool);
        });
        return true;
    }

    public function __destruct()
    {
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//            '最小链接数' => $this->minActive,//默认0
//            '最大链接数' => $this->maxActive,
//        ], '连接池对象销毁');
        $this->close();
    }

    /**
     * 定时回收
     * @param float $interval
     * @return int
     */
    protected function startBalanceTimer(float $interval)
    {

        try {
            return swoole_timer_tick(round($interval) * 1000, function () {

//                if ($this->maxActive == 500) {
//                Sys::log([
//                    $this->connectionCount,//当前连接数
//                    $this->maxActive,
//                    $this->pool->length()
//                ], '定时回收');
//                }

                $now = time();
                $validConnections = [];

//                //fonnie
//                if ($this->pool->length() == 0) {
//                    $this->close();
//                }

                while (true) {
                    if ($this->closed) {
                        break;
                    }


                    if ($this->connectionCount <= $this->minActive) {
                        break;
                    }
                    if ($this->pool->isEmpty()) {
                        break;
                    }
                    $connection = $this->pool->pop(static::CHANNEL_TIMEOUT);
                    if ($connection === false) {
                        continue;
                    }
                    $lastActiveTime = $connection->{static::KEY_LAST_ACTIVE_TIME} ?? 0;


                    if ($now - $lastActiveTime < $this->maxIdleTime) {
//                        if ($this->maxActive == 500) {
//                            clog([
//                                '最小活动连接数' => $this->minActive,
//                                '最大活动连接数' => $this->maxActive,
//                                '当前连接数' => $this->connectionCount,//当前连接数
//                                '超时时间' => $this->maxIdleTime,
//                                '运行时间' => $now - $lastActiveTime,
//                                '是否超时' => !($now - $lastActiveTime < $this->maxIdleTime),
//                                '最后一次活动的时间' => date('Y-m-d H:i:s', $lastActiveTime),
//                                '连接唯一标识' => $connection->md5 ?? '',
//                                '最后一次执行sql' => $connection->getLastSql() ?? '',
//                            ], '处理连接');
//                        }

                        $validConnections[] = $connection;
                    } else {
//                        if ($this->maxActive == 500) {
//                            clog([
//                                '最小活动连接数' => $this->minActive,
//                                '最大活动连接数' => $this->maxActive,
//                                '当前连接数' => $this->connectionCount,//当前连接数
//                                '超时时间' => $this->maxIdleTime,
//                                '运行时间' => $now - $lastActiveTime,
//                                '是否超时' => !($now - $lastActiveTime < $this->maxIdleTime),
//                                '最后一次活动的时间' => date('Y-m-d H:i:s', $lastActiveTime),
//                                '连接唯一标识' => $connection->md5 ?? '',
//                            ], '关闭连接');
//                        }

//                        Sys::log([
//                            '文件地址' => __FILE__ . ':' . __LINE__,
//                        ], '连接过期清理');
                        //过期
                        $this->removeConnection($connection);
                    }
                }

                foreach ($validConnections as $validConnection) {
                    $ret = $this->pool->push($validConnection, static::CHANNEL_TIMEOUT);
                    if ($ret === false) {
//                        Sys::log([
//                            '文件地址' => __FILE__ . ':' . __LINE__,
//                        ], '定时关闭加入队列失败清理连接');
                        $this->removeConnection($validConnection);
                    }
                }
            });
        } catch (\Throwable $e) {
            clog($e, '定时关闭连接错误');
        }
    }

    protected function createConnection()
    {
        $this->connectionCount++;
        $connection = $this->connector->connect($this->connectionConfig);


        if ($this->maxActive == 500) {
//
            //fonnie 唯一标识防止数据库缓存连接导致没有关闭连接 重复概率很低
            $md5 = bin2hex(random_bytes(16));

            $connection->md5 = $md5;

//            $dir = 'runtime/test/';
//            @mkdir($dir);
//            file_put_contents($dir . $md5 . '.json', '');

        }


        $connection->{static::KEY_LAST_ACTIVE_TIME} = time();
        return $connection;
    }

    protected function removeConnection($connection)
    {
//        try {
//            if ($this->maxActive == 500 && !empty($connection->md5)) {
//                is_file('runtime/test/' . $connection->md5 . '.json') && unlink('runtime/test/' . $connection->md5 . '.json');
//            }
//        }catch (\Throwable $e){
//
//        }

        $this->connectionCount--;
        Coroutine::create(function () use ($connection) {
            try {
                //关闭链接
                $this->connector->disconnect($connection);
                unset($connection);//手动清理对象
            } catch (\Throwable $e) {
                // Ignore this exception.
            }
        });
    }
}

<?php

namespace think\swoole\concerns;

use kss\util\Format;
use kss\util\Sys;
use Swoole\Constant;
use Swoole\Coroutine;
use Swoole\Coroutine\System;
use Swoole\Process;
use Swoole\Process\Pool;
use Swoole\Runtime;
use think\App;
use think\Collection;
use think\facade\Cache;
use think\facade\Db;
use think\swoole\coroutine\Barrier;
use think\swoole\Ipc;
use think\swoole\Watcher;

/**
 * Trait InteractsWithServer
 * @package think\swoole\concerns
 * @property App $container
 */
trait InteractsWithServer
{

    /**
     * @var array
     */
    protected $startFuncMap = [];

    protected $workerId;

    /** @var Pool */
    protected $pool;

    /** @var Ipc */
    protected $ipc;

    public function addBatchWorker(int $workerNum, callable $func, $name = null)
    {
        for ($i = 0; $i < $workerNum; $i++) {
            $this->addWorker($func, $name ? "{$name} #{$i}" : null);
        }
        return $this;
    }

    public function addWorker(callable $func, $name = null): self
    {
        $this->startFuncMap[] = [$func, $name];
        return $this;
    }

    /**
     * 启动服务
     * @param string $envName 环境变量标识
     */
    public function start(string $envName, $port = ''): void
    {


//        //自动垃圾回收
//        swoole_timer_tick(1000, function () {
//            gc_collect_cycles(); // 每分钟回收一次循环引用
////            // 清理自定义缓存
////            \think\facade\Cache::clear();
//        });


        //命令行运行内存
        ini_set('memory_limit', "1024M");
//        ini_set('memory_limit', -1);
        $this->setProcessName('manager process');
//        try {
//            //fonnie
//            //未绑定连接池去除默认redis连接
//            Cache::store('redis')->close();
//        } catch (\Throwable $e) {
////            clog($e, '去除默认redis连接');
//        }

//        try {
//            //fonnie
//            //去除默认mysql连接
//            Db::connect()->close();
//        } catch (\Throwable $e) {
//            clog($e, '去除默认mysql连接');
//        }


        //fonnie 设置协程DNS服务器
        Coroutine::set([
//            'c_stack_size' => 10 * 1024 * 1024,
//            'dns_server' => [
//                '223.5.5.5',
//                '223.6.6.6',
//                '8.8.8.8',
//                '114.114.114.114',
//            ],
//            'dns_cache_expire' => 0,
//            'log_level' => SWOOLE_LOG_TRACE,  // 等价于数字0
//            'log_date_format' => '%Y-%m-%d %H:%M:%S',
//            'log_date_with_microseconds' => true,
//            'log_file' => 'runtime/swoole_debug.log',
//            'display_errors' => true,
//            'trace_flags' => SWOOLE_TRACE_ALL, // 开启全量跟踪

//            'socket_dns_timeout' => 0.001,
//            'dns_timeout' => 0.001,

            'enable_deadlock_check' => false,//死锁检测解决热更新触发的报错
        ]);


//        Coroutine::set([
//            'dns_server' =>  '1.1.1.1',
//        ]);

        $this->initialize($port);


        $this->triggerEvent('init', [
            'port' => $port,
        ]);

        //调试模式
//        $this->app->debug(true);

        //热更新
        if ($this->getConfig('hot_update.enable', false)) {
            $this->addHotUpdateProcess();
        }


        $workerNum = count($this->startFuncMap);

        //启动消息监听
        $this->prepareIpc($workerNum);

        $pool = new Pool($workerNum, $this->ipc->getType(), 0, true);
//        clog([
//            'workerNum' => $workerNum,
//            'envName' => $envName,
//        ], '进程初始化');
//        $pool->set()
        $pool->on(Constant::EVENT_WORKER_START, function ($pool, $workerId) use ($envName) {
//
//            Coroutine::set([
//                'dns_server' => [
//                    '223.5.5.5',
//                    '223.6.6.6',
//                    '8.8.8.8',
//                    '114.114.114.114',
//                ],
////                'dns_cache_expire' => 0,
//                'enable_deadlock_check' => false,//死锁检测
//            ]);

//            Sys::log([
//                '文件地址' => __FILE__ . ':' . __LINE__,
//            ], '运行APP');

            Runtime::enableCoroutine();
//            $pool->set([
//                'worker_num' => 1,
//                'reactor_num' => 1,
//            ]);


            $this->pool = $pool;
            $this->workerId = $workerId;

            //获取当前进程的pid


            [$func, $name] = $this->startFuncMap[$workerId];

//            dump($this->pool);
//            dump($this->workerId);
//            dump($name);
            if ($name) {
                $this->setProcessName($name);
            }

//            clog([
//                'name' => $name,
//                'workerId' => $workerId,
//                'func' => $func,
//                'pool' => $pool,
//            ], '进程启动');
//
            Process::signal(SIGTERM, function () use ($workerId, $name) {

//                while ($ret = $this->pool->getProcess()->wait()) {
//                clog([
//                    $workerId,
//                    $name,
//                ], '进程关闭');

//                Coroutine::set([
//                    'enable_deadlock_check' => false,//死锁检测
//                ]);

                $this->pool->getProcess()->exit();


//                }
//                clog([$ret], '进程关闭信号');
//                clog([new Collection($this->pool)], '进程关闭信号');

            });


            $this->clearCache();
            $this->prepareApplication($envName);

            //用于监听socket客服
            $this->ipc->listenMessage($workerId);
            
            //运行定时任务
            $this->triggerEvent(Constant::EVENT_WORKER_START);

            try {
//                clog(1,'测试');
                //fonnie
                //去除默认redis连接
//                Cache::store('redis')->close();
//                $this->app->cache->store('redis')->close();
            } catch (\Throwable $e) {
//                clog($e, '去除默认redis连接');
            }
//            Sys::log([
//                '文件地址' => __FILE__ . ':' . __LINE__,
//            ], '运行APP');
            $func($pool, $workerId);

        });
        $pool->start();

    }

    public function getWorkerId()
    {
        return $this->workerId;
    }

    /**
     * 获取当前工作进程池对象
     * @return Pool
     */
    public function getPool()
    {
        return $this->pool;
    }

    public function sendMessage($workerId, $message)
    {
        $this->ipc->sendMessage($workerId, $message);
    }

    protected function prepareIpc($workerNum)
    {
        $this->ipc = $this->container->make(Ipc::class);
        $this->ipc->prepare($workerNum);
    }

    public function runWithBarrier(callable $func, ...$params)
    {
        Barrier::run($func, ...$params);
    }

    
    /**
     * 热更新
     */
    protected function addHotUpdateProcess()
    {
        $this->addWorker(function (Process\Pool $pool) {


//            try {
//                $this->container->cache->store()->handler()->close();
//            } catch (\Throwable $e) {
//
//            }

            $watcher = $this->container->make(Watcher::class);

            $watcher->watch(function () use ($pool) {
                Process::kill($pool->master_pid, SIGUSR1);
//                Process::wait(false);
//                System::waitPid($pool->master_pid);
//                clog(Process::wait(true),'a');
//                while ($ret = Process::wait(false)) {
//                    clog(1,'b');
//                }
//                Process::signal(SIGUSR1, function ($sig) {
//                    //必须为false，非阻塞模式
//                    while ($ret = Process::wait(false)) {
//                       clog(1,'b');
//                    }
//                });

            });
        }, 'hot update');
    }

    /**
     * 清除apc、op缓存
     */
    protected function clearCache()
    {
        if (extension_loaded('apc')) {
            apc_clear_cache();
        }

        if (extension_loaded('Zend OPcache')) {
            opcache_reset();
        }

//        //fonnie 主动回收内存
//        if (function_exists('gc_collect_cycles')) {
//            gc_collect_cycles();
//        }
    }

    /**
     * Set process name.
     *
     * @param $process
     */
    protected function setProcessName($process)
    {
        $appName = $this->container->config->get('app.name', 'ThinkPHP');

        $name = sprintf('swoole: %s process for %s', $process, $appName);

        @cli_set_process_title($name);
    }
}

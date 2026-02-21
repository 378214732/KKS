<?php

namespace think\swoole\concerns;

use Closure;
use think\App;
use think\Log;
use think\Model;
use think\swoole\App as SwooleApp;
use think\swoole\Manager;
use think\swoole\pool\Cache;
use think\swoole\pool\Db;
use think\swoole\Sandbox;
use Throwable;

/**
 * Trait WithApplication
 * @package think\swoole\concerns
 * @property App $container
 */
trait WithApplication
{
    /**
     * @var SwooleApp
     */
    protected $app;

    protected function prepareApplication(string $envName)
    {
        if (!$this->app instanceof SwooleApp) {
//            clog($this->app instanceof SwooleApp,'测试');
//            clog($this->app, '测试');
            $this->app = new SwooleApp($this->container->getRootPath());
            $this->app->setEnvName($envName);
//            $this->app->bind(App::class, SwooleApp::class);
            $this->app->bind(SwooleApp::class, App::class);
            $this->app->bind(Manager::class, $this);
//            $this->app->bind('log', Log::class);
            //绑定连接池
            if ($this->getConfig('pool.db.enable', true)) {

                $this->app->bind('db', Db::class);

                $this->app->resolving(Db::class, function (Db $db) {
                    $db->setLog($this->container->log);
                });

//                //模型设置数据库连接池
//                Model::setDb($this->app->db);


            }

            //绑定缓存对象
            if ($this->getConfig('pool.cache.enable', true)) {
                $this->app->bind('cache', Cache::class);
            }


            $this->app->initialize();
//            dump($this->app);
            $this->app->instance('request', $this->container->request);


            $this->prepareConcretes();

        }
    }

    /**
     * 预加载
     */
    protected function prepareConcretes()
    {
        $defaultConcretes = ['db', 'cache', 'event'];

        $concretes = array_merge($defaultConcretes, $this->getConfig('concretes', []));

        foreach ($concretes as $concrete) {
//            if ($this->app->has($concrete)) {
//                $this->app->make($concrete);
//            }

            $this->app->make($concrete);

        }


//        Model::setDb($this->app->db);

//        Model::setEvent($this->app->event);
//        Model::setInvoker([$this->app, 'invoke']);

    }

    public function getApplication()
    {
        return $this->app;
    }

    /**
     * 获取沙箱
     * @return Sandbox
     */
    protected function getSandbox()
    {
        return $this->app->make(Sandbox::class);
    }

    /**
     * 在沙箱中执行
     * @param Closure $callable
     */
    public function runInSandbox(Closure $callable)
    {
        try {
            $this->getSandbox()->run($callable);
        } catch (Throwable $e) {
            $this->logServerError($e);
        }
    }
}

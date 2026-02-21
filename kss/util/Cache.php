<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2022~2027 https://www.kasushou.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 卡速售
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace kss\util;

use think\facade\Cache as CacheStatic;
use think\facade\Env;

/**
 * 缓存工具
 * Class Cache
 * @package kss\util
 * @mixin \Redis
 */
class Cache
{

    /**
     * 过期时间
     * @var int
     */
    protected static $expire;


    /**
     * 缓存队列key
     * @var string[]
     */
    protected static $redisQueueKey = [
        0 => 'product',
        1 => 'seckill',//秒杀

    ];

    /**
     * 缓存队列key
     * @var string[]
     */
    protected static $zsetKey = [
        0 => 'docking',

    ];

    /**
     * 获取缓存过期时间
     * @param int|null $expire
     * @return int
     */
    protected static function getExpire(int $expire = null): int
    {
        if (self::$expire) {
            return (int)self::$expire;
        }
        $expire = !is_null($expire) ? $expire : 3600;
        if (!is_int($expire))
            $expire = (int)$expire;
        return self::$expire = $expire;
    }


    public static function getTagPrefix()
    {
        return md5(Env::get('kss.app_key'));
    }

    /**
     * 判断缓存是否存在
     * @param string $name
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function has(string $name): bool
    {
        try {
            return CacheStatic::has(self::getTagPrefix() . $name);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * 写入缓存
     * @param string $name 缓存名称
     * @param mixed $value 缓存值
     * @param int $expire 缓存时间，为0读取系统缓存时间
     * @return bool
     */
    public static function set(string $name, $value, int $expire = null): bool
    {
        try {
            return self::handler()->set(self::getTagPrefix() . $name, $value, $expire ?: self::getExpire($expire));
        } catch (\Throwable $e) {
            clog($e->getMessage(),'写入缓存失败');
            return false;
        }
    }

    /**
     * 如果不存在则写入缓存
     * @return mixed
     */
    public static function get(string $name, $default = false, int $expire = null)
    {
        try {
            return self::handler()->remember(self::getTagPrefix() . $name, $default, $expire ?? self::getExpire($expire));
        } catch (\Throwable $e) {
//            dump($e);
            clog($e, 'get缓存未找到');
            try {
                if (is_callable($default)) {
                    $a = $default();
//                    clog($a, 'get缓存未找到');
                    return $a;
                } else {
                    return $default;
                }
            } catch (\Throwable $e) {
//                clog($e, 'get缓存未找到1');
                return null;
            }
        }
    }


    /**
     * @return void
     */
    public static function redisSet(string $name, $default = false, int $expire = null)
    {
        try {
            return self::redisHandler()->remember($name, $default, $expire ?? self::getExpire($expire));
        } catch (\Throwable $e) {
//            dump($e);
//            clog($name, 'redisSet缓存未找到');

//            try {
//                throw new \Exception('redisSet缓存未找到');
//            }catch (\Throwable $e){
//
//            }
            try {
                if (is_callable($default)) {
                    return $default();
                } else {
                    return $default;
                }
            } catch (\Throwable $e) {
                return null;
            }
        }
    }

    /**
     * @return void
     */
    public static function redisDel(string $name)
    {
        return self::redisHandler()->delete($name);
    }


    /**
     * 如果不存在则写入缓存
     * @param string $name
     * @param bool $default
     * @return mixed
     */
    public static function remember(string $name, $default = false, int $expire = null)
    {
        try {
            return self::handler()->remember(self::getTagPrefix() . $name, $default, $expire ?? self::getExpire($expire));
        } catch (\Throwable $e) {
//            clog($name, 'remember缓存未找到');
            try {
                if (is_callable($default)) {
                    return $default();
                } else {
                    return $default;
                }
            } catch (\Throwable $e) {
                return null;
            }
        }
    }


    /**
     * 删除缓存
     * @param string $name
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function delete(string $name)
    {
        return CacheStatic::delete(self::getTagPrefix() . $name);
    }


    /**
     * 缓存句柄
     *
     * @return \think\cache\TagSet|CacheStatic
     */
    public static function handler($cacheName = '')
    {
        return CacheStatic::tag($cacheName ?: self::getTagPrefix());
    }

    /**
     * 清空缓存池
     */
    public static function clear()
    {
        return self::handler()->clear();
    }


    /**
     * Redis缓存句柄
     * @return \think\cache\TagSet|CacheStatic
     */
    public static function redisHandler(string $type = null)
    {
        if ($type) {
            return CacheStatic::store('redis')->tag(self::getTagPrefix() . $type);
        } else {
            return CacheStatic::store('redis');
        }
    }

    /**
     * 放入令牌桶
     * @param string $key
     * @param array $value
     * @param string $type
     * @return bool
     */
    public static function setTokenBucket(string $key, $value, $expire = null, string $type = 'admin')
    {
        try {
            return self::redisHandler($type)->set($key, $value, $expire);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * 清除所有令牌桶
     * @param string $type
     * @return bool
     */
    public static function clearTokenAll(string $type = 'admin')
    {
        try {
            return self::redisHandler($type)->clear();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * 清除令牌
     * @param string $type
     * @return bool
     */
    public static function clearToken(string $key, $type = 'user')
    {
        try {
            try {
                self::redisHandler()->srem(Cache::redisHandler()->getTagKey($type), $key);
            } catch (\Throwable $e) {

            }
            return self::redisHandler()->delete($key);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * 查看令牌是否存在
     * @param string $key
     * @return bool
     */
    public static function hasToken(string $key)
    {
        try {
            return CacheStatic::store('redis')->has($key);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * 获取token令牌桶
     * @param string $key
     * @return mixed|null
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function getTokenBucket(string $key)
    {
        try {
            return CacheStatic::store('redis')->get($key, null);
        } catch (\Throwable $e) {
            return null;
        }
    }


    /**
     * 获取集合数量
     */
    public static function scard($key = '', $type = 'docking')
    {
        $key = self::redisHandler()->getTagKey($type) . $key;
        return self::redisHandler()->scard($key);
    }


    /**
     * 获取集合数据
     */
    public static function smembers($key = '', $type = 'docking')
    {
        $key = self::redisHandler()->getTagKey($type) . $key;
        return self::redisHandler()->smembers($key);
    }

    /**
     * 设置redis 有序唯一集合
     */
    public static function sadd($key = '', $data = [], $type = 'docking')
    {
        if(!is_array($data)){
            $data = [$data];
        }

        if ($key && $data) {
            $key = self::redisHandler()->getTagKey($type) . $key;
            return self::redisHandler()->sadd($key, ...$data);
        }
    }

    /**
     * 弹出redis队列
     */
    public static function srem($key = '', $data = [], $type = 'docking')
    {
        if(!is_array($data)){
            $data = [$data];
        }
        if ($key) {
            $key = Cache::redisHandler()->getTagKey($type) . $key;
            return self::redisHandler()->srem($key, ...$data);
        }
    }


    /**
     * 设置redis入库
     */
    public static function pushData($name, $data)
    {
        $keyK = Env::get('cache.prefix', '');
        return self::redisHandler()->rPush($keyK . $name, serialize($data));
    }

    /**
     * 弹出redis出库
     */
    public static function popData($name, $num = 1)
    {
        $keyK = Env::get('cache.prefix', '');
        $data = self::redisHandler()->lPop($keyK . $name);
        return $data ? unserialize($data) : null;
    }

    /**
     * 队列数量
     */
    public static function lLenData($name)
    {
        $keyK = Env::get('cache.prefix', '');
        return self::redisHandler()->lLen($keyK . $name);
    }

    /**
     * 队列数据
     */
    public static function lGetData($name)
    {
        $keyK = Env::get('cache.prefix', '');
        $data = self::redisHandler()->lRange($keyK . $name, 0, -1);
        return $data;
    }

    /**
     * 检查锁
     * @param int $key
     * @param int $timeout
     * @return bool
     */
    public static function setMutex(string $key, int $timeout = 10, $count = 1)
    {

        $keyK = Env::get('cache.prefix', '');
        $readMutexKey = "redis:mutex:{$key}";
        $readMutexCountKey = "redis:mutex_count:{$key}";
        $curTime = time();


        $mutexRes = CacheStatic::store('redis')->setnx($keyK . $readMutexKey, $curTime + $timeout);
        if ($mutexRes) {
            //设置个过期时间
            CacheStatic::store('redis')->set($readMutexKey, $curTime + $timeout, $timeout + 1);
            if ($count > 1) {
                $mutexResCount = CacheStatic::store('redis')->setnx($keyK . $readMutexCountKey, $timeout);
                if ($mutexResCount) {
                    //设置个过期时间
                    CacheStatic::store('redis')->set($readMutexCountKey, $count, $timeout + 1);
                }
            }
            return true;
        }

        //判断次数
        if ($count > 1) {
            $time = CacheStatic::store('redis')->get($readMutexCountKey);
            if ($time > 0 && CacheStatic::store('redis')->decr($keyK . $readMutexCountKey) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * 删除锁
     * @param $key
     */
    public static function delMutex(string $key)
    {
        $keyK = Env::get('cache.prefix', '');
        $readMutexKey = "redis:mutex:{$key}";
        $readMutexCountKey = "redis:mutex_count:{$key}";
        //del 为原生删除
        CacheStatic::store('redis')->del($keyK . $readMutexKey);
        CacheStatic::store('redis')->del($keyK . $readMutexCountKey);
    }


    /**
     * 魔术方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::redisHandler()->{$name}(...$arguments);
    }

    /**
     * 魔术方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return self::redisHandler()->{$name}(...$arguments);
    }

}

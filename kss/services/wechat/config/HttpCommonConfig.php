<?php


namespace kss\services\wechat\config;

use kss\services\wechat\contract\ConfigHandlerInterface;
use kss\services\wechat\contract\ServeConfigInterface;
use kss\services\wechat\DefaultConfig;

/**
 * Http请求配置
 * Class HttpCommonConfig
 * @package kss\services\wechat\config
 */
class HttpCommonConfig implements ConfigHandlerInterface
{
    /**
     * @var bool[]
     */
    protected $config = [
        'verify' => false,
    ];

    /**
     * @var string
     */
    protected $serve;

    /**
     * @param string $serve
     * @return $this
     */
    public function setServe(string $serve)
    {
        $this->serve = $serve;
        return $this;
    }

    /**
     * 获取服务端实例
     * @return ServeConfigInterface
     */
    public function getServe()
    {
        return app()->make($this->serve);
    }

    /**
     * 直接获取配置
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->getServe()->getConfig(DefaultConfig::value($key), $default);
    }

    /**
     * @param string $key
     * @param $value
     * @return $this|mixed
     */
    public function set(string $key, $value)
    {
        $this->config[$key] = $value;
        return $this;
    }

    /**
     * @param string|null $key
     * @return bool|bool[]|mixed
     */
    public function get(string $key = null)
    {
        if ($key) {
            return $this->config[$key];
        }
        return $this->config;
    }

    /**
     * @return array|bool[]
     */
    public function all(): array
    {
        return $this->config;
    }
}

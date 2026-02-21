<?php

namespace kss\services\wechat\config;


use kss\services\wechat\contract\ConfigHandlerInterface;
use kss\services\wechat\DefaultConfig;

/**
 * 小程序配置
 * Class MiniProgramConfig
 * @package kss\services\wechat\config
 */
class MiniProgramConfig implements ConfigHandlerInterface
{

    /**
     * APPid
     * @var string
     */
    protected $appId;

    /**
     * APPsecret
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $responseType = 'array';

    /**
     * 日志记录
     * @var LogCommonConfig
     */
    protected $logConfig;

    /**
     * http配置
     * @var HttpCommonConfig
     */
    protected $httpConfig;

    /**
     * 是否初始化过
     * @var bool
     */
    protected $init = false;

    /**
     * MiniProgramConfig constructor.
     * @param LogCommonConfig $config
     * @param HttpCommonConfig $commonConfig
     */
    public function __construct(LogCommonConfig $config, HttpCommonConfig $commonConfig)
    {
        $this->logConfig = $config;
        $this->httpConfig = $commonConfig;
    }

    /**
     * 初始化
     */
    protected function init()
    {
        if ($this->init) {
            return;
        }
        $this->init = true;

        //获取插件
//        $addons_config = get_addons_config('login');
//        $this->appId = $this->appId ?: $addons_config['wxxcx_appid'];
//        $this->secret = $this->secret ?: $addons_config['wxxcx_secret'];
        $this->appId = $this->appId ?: $this->httpConfig->getConfig('miniprogram.appid', '');
        $this->secret = $this->secret ?: $this->httpConfig->getConfig('miniprogram.secret', '');
//        $this->token = $this->token ?: $this->httpConfig->getConfig('official.token', '');
//        $this->aesKey = $this->aesKey ?: $this->httpConfig->getConfig('official.key', '');
    }


    /**
     * 获取配置
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->httpConfig->getConfig($key, $default);
    }

    /**
     * 设置
     * @param string $key
     * @param $value
     * @return $this|mixed
     */
    public function set(string $key, $value)
    {
        $this->{$key} = $value;
        return $this;
    }

    /**
     * @param string|null $key
     * @return array|mixed
     */
    public function get(string $key = null)
    {
        $this->init();
        if ('log' === $key) {
            return $this->logConfig->all();
        }
        if ('http' === $key) {
            return $this->httpConfig->all();
        }
        return $this->{$key};
    }

    /**
     * 全部
     * @return array
     */
    public function all(): array
    {
        $this->init();
        return [
            'app_id' => $this->appId,
            'secret' => $this->secret,
            'response_type' => $this->responseType,
            'log' => $this->logConfig->all(),
            'http' => $this->httpConfig->all()
        ];
    }
}

<?php


namespace kss\services\wechat\config;


use kss\services\wechat\contract\ConfigHandlerInterface;
use kss\services\wechat\DefaultConfig;

/**
 * 公众号配置
 * Class OfficialAccountConfig
 * @package kss\services\wechat\config
 */
class OfficialAccountConfig implements ConfigHandlerInterface
{

    /**
     * AppID
     * @var string
     */
    protected $appId;

    /**
     * AppSecret
     * @var string
     */
    protected $secret;

    /**
     * Token
     * @var string
     */
    protected $token;

    /**
     * EncodingAESKey
     * @var string
     */
    protected $aesKey;

    /**
     * 指定 API 调用返回结果的类型
     * @var string
     */
    protected $responseType = 'array';

    /**
     * @var LogCommonConfig
     */
    protected $logConfig;

    /**
     * @var HttpCommonConfig
     */
    protected $httpConfig;

    /**
     * @var bool
     */
    protected $init = false;

    /**
     * OfficialAccountConfig constructor.
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
//        dump(123);
        $this->init = true;
        $this->appId = C(DefaultConfig::value('official.appid'));
        $this->secret = C(DefaultConfig::value('official.secret'));
        $this->token = C(DefaultConfig::value('official.token'));
        $this->aesKey = C(DefaultConfig::value('official.key'));
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
     * 获取配置
     * @param string|null $key
     * @return array|mixed|null
     */
    public function get(string $key = null)
    {
        $this->init();
        if ($key) {
            if (isset($this->{$key})) {
                return $this->{$key};
            }
            if ('log' === $key) {
                return $this->logConfig->all();
            }
            if ('http' === $key) {
                return $this->httpConfig->all();
            }
        }
        return null;
    }

    /**
     * 获取所有配置
     * @return array
     */
    public function all(): array
    {
        $this->init();
        return [
            'app_id' => $this->appId,
            'secret' => $this->secret,
            'token' => $this->token,
            'aes_key' => $this->aesKey,
            'response_type' => $this->responseType,
            'log' => $this->logConfig->all(),
            'http' => $this->httpConfig->all()
        ];
    }
}

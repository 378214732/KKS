<?php


namespace kss\base;


/**
 * 容器基类
 * Class BaseStorage
 * @package kss\base
 */
class BaseStorage
{
    use \kss\traits\ErrorTrait;

    /**
     * 驱动名称
     * @var string
     */
    protected $name;

    /**
     * BaseStorage constructor.
     * @param string $name 驱动名
     * @param string $configFile 驱动配置名
     * @param array $config 其他配置
     */
    public function __construct(string $name, array $config = [], string $configFile = null)
    {
        $this->name = $name;
        $this->configFile = $configFile;
        $this->initialize($config);
    }

    /**
     * 初始化
     * @param array $config
     * @return mixed
     */
    protected function initialize(array $config)
    {

    }

}

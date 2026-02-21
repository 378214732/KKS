<?php

namespace kss\services\wechat\contract;

/**
 * 配置
 * Interface ConfigHandlerInterface
 * @package kss\services\wechat\contract
 */
interface ConfigHandlerInterface
{

    /**
     * 设置
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value);

    /**
     * 获取单个
     * @param string|null $key
     * @return mixed
     */
    public function get(string $key = null);

    /**
     * 获取全部
     * @return array
     */
    public function all(): array;
}

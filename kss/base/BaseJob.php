<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2022~2027 https://www.kasushou.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 卡速售
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace kss\base;

use think\queue\Job;

/**
 * 消息队列
 * Class BaseJob
 * @package kss\base
 */
class  BaseJob
{

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        $this->fire(...$arguments);
    }

    /**
     * 运行消息队列
     * @param Job $job
     * @param $data
     */
    public function fire(Job $job, $data): void
    {
        try {
            $action = $data['do'] ?? 'doJob';//任务名
            $infoData = $data['data'] ?? [];//执行数据
            $failNum = $data['failNum'] ?? 1;//最大错误次数

//            clog([$data], '队列基类调用方法');
            $this->runJob($action, $job, $infoData, $failNum);

        } catch (\Throwable $e) {
            $job->delete();
        }
    }

    /**
     * 执行队列
     * @param string $action
     * @param Job $job
     * @param array $infoData
     * @param int $failNum
     */
    protected function runJob(string $action, Job $job, array $infoData, int $failNum = 1)
    {

        $action = method_exists($this, $action) ? $action : 'handle';

//        clog([$action, $infoData], '队列基类运行队列');
        if (!method_exists($this, $action)) {
            $job->delete();
        }
        if ($this->{$action}(...$infoData)) {
            //删除任务
            $job->delete();
        } else {
            if ($job->attempts() >= $failNum && $failNum) {
                //删除任务
                $job->delete();
            } else {
                //从新放入队列
                $job->release();
            }
        }
    }
}
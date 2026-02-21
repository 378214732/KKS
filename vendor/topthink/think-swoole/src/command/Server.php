<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think\swoole\command;

use kss\util\Sys;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\swoole\Manager;

class Server extends Command
{
    public function configure()
    {
        $this->setName('swoole')
            ->addOption(
                'env',
                'E',
                Option::VALUE_REQUIRED,
                'Environment name',
                ''
            )
            ->addOption('port', 'p', Option::VALUE_REQUIRED, 'Server port', '')
            ->setDescription('Swoole Server for ThinkPHP');
    }

    protected function execute(Input $input, Output $output)
    {
        //设置北京时区
        date_default_timezone_set('PRC');

        $port = $this->input->getOption('port');
//        $manager = new Manager();
        return $this->app->invoke([$this, 'handle'], [$port]);
    }

    public function handle($port, Manager $manager)
    {

        $this->checkEnvironment();

        $this->output->writeln('Starting swoole server...');

        $this->output->writeln('You can exit with <info>`CTRL-C`</info>');
//
        //取餐码监测是否启动
        if (config('kss.search_card_link')) {
            $dir = root_path('addons/dockingtask/services/docking/client');
            if (is_file($dir . '/searchLink')) {
                $cmd = 'cd ' . $dir . ' && ./searchLink restart';
                $res = Sys::run($cmd);
                if (!empty($res['data']) && stripos($res['data'], '服务已启动') !== false) {
                    $this->output->writeln('searchLink服务已启动');
                } else {
                    $this->output->writeln('searchLink服务启动失败');
                }
            } else {
                $this->output->writeln('searchLink服务启动失败');
            }
        }



        $envName = $this->input->getOption('env');
        $manager->start($envName, $port);
    }

    /**
     * 检查环境
     */
    protected function checkEnvironment()
    {
        if (!extension_loaded('swoole')) {
            $this->output->error('Can\'t detect Swoole extension installed.');

            exit(1);
        }

        if (!version_compare(swoole_version(), '4.6.0', 'ge')) {
            $this->output->error('Your Swoole version must be higher than `4.6.0`.');

            exit(1);
        }
    }
}

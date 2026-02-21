<?php

namespace app\controller\admin\v1\sys;

use kasushou\addons\Service;
use kasushou\helper\FileHelper;
use kasushou\helper\ZipHelper;
use kss\base\BaseController;
use kss\services\addon\AddonService;
use kss\services\addon\AuthCloudService;

class Template extends BaseController
{
    protected $authCloudService;


    public function __construct()
    {

        $app = app();
        parent::__construct($app);
        $this->adminId = $this->request->hasMacro('adminId') ? $this->request->adminId() : 0;
        $this->admin = $this->request->hasMacro('admin') ? $this->request->admin() : [];


//        if ($this->request->adminId() != 1 && in_array($this->request->action(), [
//                'install',
//                'uninstall',
//                'local',
//                'updateAddon',
//                'updateAddonVersion',
//
//
//            ])) {
//            $this->error('暂无权限');
//        }

//        $this->modelClass = new AddonModel();
//        $this->addonService = new AddonService();
        $this->authCloudService = AuthCloudService::instance();
//        $this->app_version = $this->authCloudService->getAppVersion();


        $noNeedLogin = [
            'login',
            'register',
            'sendsms',
            'getversion',
        ];
        if (!in_array(strtolower($this->request->action()), $noNeedLogin)) {
            try {
                $this->authCloudService->parseToken();
            } catch (\Throwable $e) {
                $this->error('请先登录市场', [], 710000);
            }
        }


    }

    /**
     * 模板应用列表
     */
    public function index()
    {
        try {
            $params = $this->request->post();
            $result = $this->authCloudService->setApiUrl('/kss/template/index')
                ->setParams($params)
                ->run();
            if ($result['code'] == 200) {
                $data = $result['data'];

//                clog($result,'aaaaaaaa');
                if (is_array($data['list'])) {
                    foreach ($data['list'] as &$item) {
                        //是否已经安装过
                        if (!empty($localAddons[$item['name']])) {
                            try {


//                                $info = get_addons_info($item['name']);
//                                $item['install'] = $info['install'];
//                                $item['status'] = $info['status'];
//                                $item['buyed_version'] = $info['version'];
//                                //对比本地是否需要更新
//                                if (!check_version($info['version'], $item['version'])) {
//                                    $item['update_version'] = 1;
//                                }
//
//                                //更新基本信息
//                                if (!empty($item['is_update_info'])) {
////
////                                    $update_info = [];
////                                    foreach ($item['is_update_info'] as $v) {
////                                        if (isset($item[$v])) {
////                                            $update_info[$v] = is_array($item[$v]) ? json_encode($item[$v], 256) : $item[$v];
////                                        }
////                                    }
////                                    if ($update_info) {
////                                        set_addons_info($item['name'], $update_info);
////                                    }
//
//                                }

                            } catch (\Throwable $e) {

                            }


//                        $item['old_'] = $info['status'];
                        } else {
                            $item['status'] = 1;
//                        $item['is_buy'] = 0;

                        }
                    }
                }
            }


//            $this->update_call();
            return success($data ?? []);

        } catch (\Throwable $e) {
            apiError($e->getMessage());
        }
    }

    /**
     * 插件分类
     */
    public function indexCate()
    {
        try {
            $data = $this->request->post();
            $result = $this->authCloudService->setApiUrl('/kss/template/indexCate')
                ->setParams($data)
                ->run();
            if ($result['code'] == 200) {
                return success($result['data']);
            } else {
                return success([]);
            }
        } catch (\Throwable $e) {
            apiError($e->getMessage());
        }
    }

    /**
     * 购买插件
     */
    public function buy()
    {
        if (is_demo()) {
            $this->error('演示站点无权限');
        }
        try {
            $data = $this->request->post();
            $result = $this->authCloudService->setApiUrl('/kss/template/buy')
                ->setParams($data)
                ->run();
//            clog($result,'测试测试');
            if ($result['code'] == 200) {

//                clog($result, '购买模板');
                //更新本地插件key
//                $return_data = $result['data'] ?? [];
//                $this->updateAddonToken($return_data);

//                $this->update_call();
                return success('购买成功');
            } else {
                return error($result['msg'], null, $result['code'] ?? 400);
            }
        } catch (\Throwable $e) {
            apiError($e->getMessage());
        }
    }


    /**
     * 更新插件和授权
     * @param $data
     * @return void
     */
    private function updateAddonToken($data = [])
    {
        //更新token
        if (!empty($data['token'])) {
            $this->authCloudService->setAuth($data['token']);
        }


        //获取当前插件
        //保存插件
        if (is_array($data['addon'])) {

            // clog($data['addon'], 'ServeAddon1');
            $addon_list = get_addons_list();
            $fileDir = runtime_path('addons');
            if (!is_dir($fileDir)) {
                FileHelper::mkdirs($fileDir);
            }

//            $addon_count_key = md5(json_encode($data['addon']));
//            $addon_count = count($data['addon']);
//            $install_count = 0;
//            \kss\util\Cache::redisHandler('AuthCloud')->set($addon_count_key, $install_count);
//

            //获取当前插件
            foreach ($data['addon'] as $item) {

                //安装插件
//                if (!empty($addon_list[$item['addon']['name']])) {
//                    try {
//                        set_addons_info($item['addon']['name'], [
//                            'key' => $item['key'],
//                        ]);
////                        Cache::set($addon_count_key, Cache::get($addon_count_key) + 1);
//                    } catch (\Throwable $e) {
//                    }
//                } else {

//                    if (!empty($item['file_url'])) {
//                        $host = request()->host();
//                        //本地没有插件
////                        call_go(function () use ($item, $fileDir, $host, $addon_count_key) {
//
//                        //直接存
//
//                        try {
//
//                            $content = $this->getFileContent($item['file_url']);
//                            //判断无效
//                            if ($content) {
//
//                                $fileName = $fileDir . $item['addon']['name'] . '.zip';
//                                @touch($fileName);
//                                file_put_contents($fileName, $content);
//
//
//                                $install_dir = runtime_path('addons/update');
//                                if (!is_dir($install_dir)) {
//                                    FileHelper::mkdirs($install_dir);
//                                }
//                                ZipHelper::unzip($fileName, $install_dir);
//                                @unlink($fileName);
//
//                                //复制文件
//                                FileHelper::copyDir($install_dir, root_path('addons'));
//                                FileHelper::delDir($install_dir);
//
//                                try {
//                                    $this->install($item['addon']['name'], '', $host);
//                                } catch (\Throwable $e) {
//                                    clog($e, '安装插件失败');
//                                }
//
//                                try {
//                                    set_addons_info($item['addon']['name'], [
//                                        'key' => $item['key'],
//                                    ]);
//                                } catch (\Throwable $e) {
//                                }
//
//
//                                //运行函数
//                                try {
////                                    $this->run_function($version);
//                                } catch (\Throwable $e) {
//
//                                }
//
//                            }
//
//                        } catch (\Throwable $e) {
//
//                            clog($e->getMessage(), '安装插件失败');
//                        }
//
////                            \kss\util\Cache::redisHandler('AuthCloud')->set($addon_count_key, 1 + \kss\util\Cache::redisHandler('AuthCloud')->remember($addon_count_key, 0));
////                        });
//                    }
//                }

            }


            //循环获取是否安装完成

//
//            $install_count = \kss\util\Cache::redisHandler('AuthCloud')->remember($addon_count_key, 0);
//            while ($install_count < $addon_count) {
//                sleep(1);
//                $install_count = \kss\util\Cache::redisHandler('AuthCloud')->remember($addon_count_key, 100);
//            }
//            \kss\util\Cache::clearToken($addon_count_key, 'AuthCloud');

        }

        if (empty($data['upgradeApp'])) {
            $this->upgradeApp($data['upgradeApp']);
        }

    }


    /**
     * 更新进程
     */
    private function update_call($is_update = true)
    {
        if (function_exists('apc_clear_cache')) {
            @apc_clear_cache();
        }
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }

        try {
            //清除缓存thinkphp表字段缓存
            clearData();
        } catch (\Throwable $e) {

        }

        if ($is_update) {
            call_go(function () {
//                clog([], '热更新重启进程');
                $manager = app(\think\swoole\Manager::class);
                \Swoole\Process::kill($manager->getPool()->master_pid, SIGUSR1);
            }, 500);
        } else {
            $is_update_call = \kss\util\Cache::remember('AuthCloud_is_update_call');
            if ($is_update_call) {
                call_go(function () {
//                    clog([], '热更新重启进程');
                    \kss\util\Cache::delete('AuthCloud_is_update_call');
                    $manager = app(\think\swoole\Manager::class);
                    \Swoole\Process::kill($manager->getPool()->master_pid, SIGUSR1);
                }, 500);


            }
        }

    }


    private function getFileContent($url = '')
    {
        if (stripos($url, 'https://gitee.com/api/v5/repos') !== false) {
            $content = file_get_contents($url);
            if ($content) {
                $return = json_decode($content, true);
                if ($return) {
                    $content = base64_decode($return['content']);
                } else {
                    $content = '';
                }
            }
        } else {
            $content = file_get_contents($url . "?t=" . time());
        }
        return $content;
    }


    /**
     * 安装模板
     * @return void
     */
    public function install($name = '', $type = '', $host = '')
    {
        if (is_demo()) {
            $this->error('演示站点无权限');
        }
        set_time_limit(0);
        $name = $this->request->param("name") ?? $name;
        $sku_id = $this->request->param("sku_id");
        $version = $this->request->param("version");
        $type = $this->request->param("type") ?? $type;

//        模板名是否为空
        if (!$name) {
            $this->error(lang('template  %s can not be empty', [$name]));
        }
        //插件名是否符合规范
        if (!preg_match("/^[a-zA-Z0-9]+$/", $name)) {
            $this->error(lang('template name is not right'));
        }

        //本地存在空和更新则请求后端
//        if (empty($type) || $type == 'upgrade') {
        //不存在或者
        $params = [
            'sku_id' => $sku_id,
            'name' => $name,
            'version' => $version ?? '',
            'type' => $type,
            "ip" => request()->ip(),
        ];
        //判断是否有域名
        if ($host) {
            $this->getCloundAddons($params, ['host' => $host]);
        } else {
            $this->getCloundAddons($params);
        }

//        $class = get_addons_instance($name);
//        if (empty($class)) {
//            return error(lang('template %s is not ready', [$name]));
//        }
//        //添加数据库
//        try {
//            importsql($name);
//        } catch (\Throwable $e) {
//            return error($e->getMessage());
//        }
//
//
//        // 安装菜单
//        $menu_config = get_addons_menu($name);
//
//        if (!empty($menu_config)) {
//            $this->addonService->addAddonMenu($menu_config);
//        }
//
//
//        //安装插件
//        $class->install();

//        $addon_info = get_addons_info($name);
//        if (empty($addon_info)) {
//            clog($name, '安装插件失败');
//            return error(lang('addon install fail'));
//        }

        //获取插件密钥
//        $this->getAddonsKey($addon_info);
//        $addon_info['status'] = 1;
        Service::copyApp($name, $delete = true);
        //复制文件到目录
        if (Service::getCheckDirs()) {
            foreach (Service::getCheckDirs() as $k => $dir) {
                $sourcedir = Service::getAddonsNamePath($name) . $dir;
                if (is_dir($sourcedir)) {
                    //路由复制到路由目录
                    if ($k && !is_numeric($k)) {
                        FileHelper::copyDir($sourcedir, app()->getRootPath() . $k, true);
                    } else {
                        FileHelper::copyDir($sourcedir, app()->getRootPath() . $dir . DS . 'static' . DS . 'addons' . DS . $name, true);
                    }
                }
            }
        }
        try {
//            Service::updateAddonsInfo($name);
            //刷新addon文件
//            refreshaddons();
        } catch (\Throwable $e) {
            clog($e->getMessage(), '更新插件文件错误');
            return error($e->getMessage());
        }
//        Cache::clear();

        //直接调用
        if ($host) {


        } else {
            if ($type == 'upgrade') {
                $this->update_call();
                return success('升级成功');
            } else {
                $this->update_call();
                return success(lang('安装成功,请前往页面管理查看'));
            }
        }


    }

    /**
     * 获取远程安装包
     * @param $params
     * @return void
     * @throws \Throwable
     */
    protected function getCloundAddons($params, $option = [])
    {
//        if (is_demo()) {
//            $this->error('演示站点无权限');
//        }
        try {
            $res = $this->authCloudService->setApiUrl('/kss/template/down')
                ->setParams($params, $option)
                ->run();
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }

//        clog([$params, $option, $res], '下载安装模板');
        if ($res['code'] != 200) {
            $this->error($res['msg']);
        }

        $fileDir = runtime_path('diy');

        if (!is_dir($fileDir)) {
            FileHelper::mkdirs($fileDir);
        }


        $content = $this->getFileContent($res['data']['file_url']);

        if ($content) {

            $version = $res['data']['version'];

            $fileName = $fileDir . $params['name'] . '.zip';

            @touch($fileName);
            file_put_contents($fileName, $content);
            if (!empty($res['data']['install_dir'])) {
                if ($res['data']['install_dir'] == 1) {
                    $install_dir = root_path();
                }
            }

            $this->toInstall($fileName, $params, $version, $install_dir);


        } else {
            $this->error('文件获取失败');
        }


    }

    private function toInstall($fileName, $params = [], $version = '', $install_dir = '')
    {
        empty($version) && $version = '1.0.0';
        empty($install_dir) && $install_dir = root_path('');

        if (!is_dir($install_dir)) {
            FileHelper::mkdirs($install_dir);
        }
        ZipHelper::unzip($fileName, $install_dir);
        @unlink($fileName);


        //获取更新sql
        $sql_file = runtime_path('diy/' . $params['name']) . $version . '.sql';
        if (is_file($sql_file)) {
            try {
                run_sql($sql_file);
                @unlink($sql_file);
            } catch (\Throwable $e) {

            }
        }

        //运行函数
        try {
            $this->run_function($version, $params['name']);
        } catch (\Throwable $e) {

        }
//
//            //插件更新form 配置文件
//            try {
//                $this->updateAddonConfig($version,   $params['name']);
//            } catch (\Throwable $e) {
//
//            }

        try {
            //获取更新kss.php
            $this->setKssConfig($version, $params['name']);
        } catch (\Throwable $e) {

        }
        try {
            //安装模板
            $this->addTemplate($params['name']);
        } catch (\Throwable $e) {

        }

        //转移config.json
        try {

            $newConfigFilePath = app_path("common/template/{$params['name']}");
            if (!is_dir($newConfigFilePath)) {
                FileHelper::mkdirs($newConfigFilePath);
            }

            $newConfigFile = $newConfigFilePath . 'config.json';

            //pc模板
            $config_file = public_path('template/front/' . $params['name']) . 'config.json';
            if (is_file($config_file)) {
                @copy($config_file, $newConfigFile);
                @unlink($config_file);
            }
            //h5模板
            $config_file = public_path('template/h5/' . $params['name']) . 'config.json';
            if (is_file($config_file)) {
                @copy($config_file, $newConfigFile);
                @unlink($config_file);
            }

            if (is_file($newConfigFile)) {
                $newConfig = json_decode(file_get_contents($newConfigFile), true);
                if (!empty($newConfig['page']) && !empty($newConfig['key'])) {
                    //更新授权
                    $newConfig['authKey'] = L('SysUrlLogic')->createTemplatePageAuthKey([
                        'n' => $newConfig['page'],
                        'v' => $newConfig['key'],
                        'e' => '',
                        'h' => '',
                        't' => time(),
                    ]);
                    file_put_contents($newConfigFile, json_encode($newConfig, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                }

            }

        } catch (\Throwable $e) {
        }
    }

    public function addTemplate($template_name = '')
    {
        $template_file = runtime_path('diy/' . $template_name) . 'install.kss';
        if (is_file($template_file)) {
            try {
                L('DiyTemplateLogic')->importData([], [
                    'file_path' => $template_file,
                    'is_sys' => 1,
                ]);
            } catch (\Throwable $e) {
            }
            @unlink($template_file);
        }
    }

    /**
     * 更新配置文件
     */
    public function setKssConfig($version, $template_name = '')
    {
        $config_file = runtime_path('diy/' . $template_name) . $version . '.php';
        if (is_file($config_file)) {
            try {
                $array = include_once $config_file;
                if ($array) {
                    $config = config('kss');
                    foreach ($array as $k => $v) {
                        //是否为合并
                        $is_type2 = false;
                        if (!empty($v['type'])) {
                            if (isset($config[$k]) && $v['type'] == 2 && is_array($config[$k])) {
                                $is_type2 = true;
                            }
                        }
                        if ($is_type2) {
                            //重置下标问题 array_merge
                            $config[$k] = array_replace($config[$k], $v['data'] ?? []);
                        } else {
                            $config[$k] = $v['data'] ?? '';
                        }
                    }
                    setConfigFile('kss', $config);
                }
                @unlink($config_file);
            } catch (\Throwable $e) {

            }
        }
    }


    /**
     * 运行函数
     */
    private function run_function($version = '', $template_name = '')
    {
        if (empty($version)) {
            return false;
        }

        $sys_file = runtime_path('diy/' . $template_name) . $version . 'F.php';

        try {
            if (is_file($sys_file)) {
                include_once $sys_file;
                $version = str_replace('.', '', $version);
                $sys_function = 'diy' . $template_name . $version;
                if (function_exists($sys_function)) {
                    $sys_function();
                }
            }
        } catch (\Throwable $e) {

        }
        if (is_file($sys_file)) {
            unlink($sys_file);
        }

    }

    /**
     * 上传更新
     */
    public function updateSys()
    {

        $file = $this->request->file('file');
        if ($file) {
            if (is_demo()) {
                return error('演示站点无权限');
            }

            $file_name = $file->getOriginalName();
            //后缀必须是zip
            if (substr($file_name, -4) !== '.zip') {
                return error('安装包有误!');
            }

            $left_length = stripos($file_name, ".") - 1;
            $right_length = strrpos($file_name, ".");
            $name = substr($file_name, 0, $left_length);
            $version = substr($file_name, $left_length, $right_length - $left_length);

            if (in_array($name, [
                'default',
                'diy'
            ])) {
                return error('安装包有误!');
            }

            if (empty($name) || empty($version)) {
                return error('安装包有误!');
            }

            if ($name == $version) {
                $version = '1.0.0';
            }
            $install_dir = '';;
//
//            if (request()->host() == 'new.ezhancn.com') {
//                $install_dir = public_path('template/front/' . $name);
//            }



            $this->toInstall($file->getPathname(), [
                'name' => $name,
            ], $version, $install_dir);

            return success('安装成功,请前往自助绑定域名修改模板');
        } else {
            return error('安装包有误!');
        }


    }


}
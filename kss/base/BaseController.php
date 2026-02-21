<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2022~2027 https://www.kasushou.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 卡速售
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace kss\base;


use think\exception\HttpResponseException;
use think\App;
use think\facade\Lang;


class BaseController
{

    /**
     * BaseRequest实例
     * @var BaseRequest
     */
    protected $request;

    /**
     * 应用实例
     * @var App
     */
    protected App $app;


    /**
     * @var BaseLogic
     */
    protected $logic = null;

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     * @param BaseLogic|null $logic
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = app()->request;
        $this->initialize();
        $this->loadlang();

    }

    //自动加载语言
    protected function loadlang()
    {
        //获取语言包
        $lang_file = app_path() . 'lang' . DS . \think\facade\Lang::getLangSet() . DS . str_replace('.', DS, strtolower(app()->request->controller())) . '.php';
        if (is_file($lang_file)) {
            \think\facade\Lang::load($lang_file);
        }
    }


    public function initialize()
    {

    }

    protected function getModule()
    {
        $class = get_class($this);
        list(, , $name) = explode('\\', $class);

        //如果是分站使用后台验证器
        if ($name == 'vip') {
            $name = 'admin';
        }

        return $name;
    }

    /**
     * 验证数据
     * @access protected
     * @param string|array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param bool $is_scene 是否验证名
     * @return array|string|true
     */
    protected function validate($data = '', $validate = '', $is_scene = true)
    {
        if (is_array($data)) {
            $input = $data;
        } else {
            $input = input('post.');
        }

        //默认验证方法
        if (is_string($data)) {
            if (strpos($data, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $data);
            } else {
                $scene = $data;
            }
        } else {
            if ($is_scene) {
                //默认方法验证
                $scene = $this->request->action();
            } else {
                //默认方法验证
                $scene = null;
            }

        }


        //默认控制器验证
        if (!$validate) {
            $validate = app()->parseClass('validate', $this->request->controller());
            if (!class_exists($validate)) {
                //不存在则不验证
                return true;
            }
            validate($validate)->scene($scene)->check($input);
        } else {
            $validate = app()->parseClass('validate' . '\\' . $this->getModule(), $validate);
            validate($validate)->scene($scene)->check($input);
        }


    }

    /**
     * 操作成功返回的数据
     * @param string $msg 提示信息
     * @param mixed $data 要返回的数据
     * @param int $code 错误码，默认为1
     * @param array|null $replace
     * @param array $header 发送的 Header 信息
     */
    public function success($msg = 'ok', $data = null, $code = 200, ?array $replace = ['code' => 200], $header = [])
    {
        $response = success($msg, $data, $code, $replace, $header);
        throw new HttpResponseException($response);
    }

    /**
     * 操作失败返回的数据
     * @param string $msg 提示信息
     * @param mixed $data 要返回的数据
     * @param int $code 错误码，默认为0
     * @param array|null $replace
     * @param array $header 发送的 Header 信息
     */
    public function error($msg = 'fail', $data = null, $code = 400, ?array $replace = ['code' => 200], $header = [])
    {
        $response = error($msg, $data, $code, $replace, $header);
        throw new HttpResponseException($response);
    }


    /**
     * 重复调用检测
     * @param $msg
     * @param $time
     * @param $key
     * @return void
     */
    protected function checkTwo($msg = '', $key = '', $time = 1, $count = 1)
    {
//        !$this->check_two($time, $key, $count) && apiError($msg ?: '老板，路上有点儿堵，请您稍等！');
        //请求频繁不提示用户
        !$this->check_two($time, $key, $count) && apiError($msg ?: '');
    }


    /**
     * 验证是否两次发送检查锁
     * @param int $key
     * @param int $timeout
     * @return bool
     */
    private function check_two(int $timeout = 1, string $key = '', $count = 1)
    {
        //测试
        if (is_test()) {
            return true;
        }
        !$key && $key = md5(env('cache.prefix') . app()->request->host() . app()->request->ip() . app()->request->controller() . app()->request->action());
        return \kss\util\Cache::setMutex($key, $timeout, $count);
    }


    /**
     * 删除锁
     * @param $key
     */
    private function check_two_del(string $key = '')
    {
        !$key && $key = md5(env('cache.prefix') . app()->request->host() . app()->request->ip() . app()->request->controller() . app()->request->action());
        \kss\util\Cache::delMutex($key);
    }

}
<?php
// +----------------------------------------------------------------------
// | 卡速售
// +----------------------------------------------------------------------
// | Copyright (c) 2022~2027 https://www.kasushou.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 卡速售
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace kss\base;


use Spatie\Macroable\Macroable;
use think\Request;

/**
 * Class BaseRequest
 * @package kss\base
 * @method tokenData() 获取token信息
 * @method user($key = null) 获取用户信息
 * @method userId() 获取用户userId
 * @method admin($key = null) 获取管理员信息
 * @method adminId() 管理员ID
 * @method vip($key = null) 获取分站信息
 * @method vipId() 分站ID
 * @method merchant($key = null) 获取供货商
 * @method merchantId() 商户ID
 * @method kefu($key = null) 客服信息
 * @method kefuId() 客服Id
 * @method isLogin() 获取登录状态
 */
class BaseRequest extends Request
{
//    use Macroable;


    public $tokenData; //获取token信息
    public $user; //获取用户信息
    public $userId; //获取用户uid

    public $admin; //后台管理员
    public $adminId; //后台管理员id
    public $kefuId; //客服id
    public $kefu; //客服信息
    public $vipId; //分站ID
    public $vip; //分站
    public $merchantId; //供货商ID
    public $merchant; //供货商


    /**
     * 登录保存参数
     * @var array
     */
    protected $loginParam = [
        'tokenData', 'isLogin',
        'user', 'userId', 'admin', 'adminId',
        'vip', 'vipId', 'merchantId', 'merchant',
        'kefu', 'kefuId',
    ];

    /**
     * 不过滤变量名
     * @var array
     */
    protected array $except = [

    ];

    /**
     * 获取请求的数据
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */
    public function more(array $params, bool $suffix = true, bool $filter = true): array
    {

        // 字段 默认值 过滤函数 字段别名
        // [字段,字段类型] 默认值 过滤函数 字段别名
        $p = [];
        $i = 0;
        foreach ($params as $param) {
            if (!is_array($param)) {
                $p[$suffix == true ? $i++ : $param] = $this->filterWord(is_string($this->param($param)) ? trim($this->param($param)) : $this->param($param), $filter && !in_array($param, $this->except));
            } else {


                if (!isset($param[1])) $param[1] = null;
                if (!isset($param[2])) $param[2] = '';

                if (is_array($param[0])) {
                    $keyName = $param[0][0];

                    if (in_array($param[0][1], [
                        'd',
                        'f',
                        'a',
                        'b'
                    ])) {

                        $name = is_array($param[1]) ? $param[0][0] . '/a' : $param[0][0] . '/' . $param[0][1];
                    } else { //where处理

                        $name = $param[0][0];
//                        if (strpos($name, '.')) {//join处理
//                            $name = explode('.', $name)[1];
//                        }
                        $value = $this->filterWord(is_string($this->param($name, $param[1], $param[2])) ? trim($this->param($name, $param[1], $param[2])) : $this->param($name, $param[1], $param[2]), $filter && !in_array($keyName, $this->except));

                        if (($value === '' && is_null($param[1])) || is_null($value)) {

                        } else {

                            if (!empty($param[1]) && $value === '') {
                                $value = $param[1];
                            }

//                            clog([$param, $keyName, $value], '测色彩');

                            $where = $this->buildWhere((isset($param[3]) ? $param[3] : $keyName), $param[0][1], $value);
                            $where && $p = array_merge($p, $where);
                        }


                        continue;

                    }


                } else {
                    $name = is_array($param[1]) ? $param[0] . '/a' : $param[0];
                    $keyName = $param[0];
                }


                $p[$suffix == true ? $i++ : (isset($param[3]) ? $param[3] : $keyName)] = $this->filterWord(is_string($this->param($name, $param[1], $param[2])) ? trim($this->param($name, $param[1], $param[2])) : $this->param($name, $param[1], $param[2]), $filter && !in_array($keyName, $this->except));
            }
        }

        return $p;
    }


    public function buildWhere($key, $op, $val)
    {


//        $v = stripos($v, ".") === false ? $tableName . $v : $v;

        $where = [];
        switch (strtoupper($op)) {
            case '=':
                $where[] = [$key, '=', $val];
                break;
            case 'IN':
                $val = is_array($val) ? $val : explode(',', $val);
                $where[] = [$key, 'IN', $val];
                break;
            case 'NOT IN':
                $val = is_array($val) ? $val : explode(',', $val);
                $where[] = [$key, 'NOT IN', $val];
                break;
            case '%*%':
            case 'LIKE':
                $where[] = [$key, 'LIKE', "%{$val}%"];
                break;
            case '*%':
                $where[] = [$key, 'LIKE', "{$val}%"];
                break;
            case '%*':
                $where[] = [$key, 'LIKE', "%{$val}"];
                break;
            case 'BETWEEN':
                if (is_array($val)) {
                    $arr = $val;
                    if (count($arr) != 2) {
                        return false;
                    }
                } else {
                    $arr = explode('-', $val);
                    if (count($arr) != 2) {
                        return false;
                    }
                }
                [$begin, $end] = [$arr[0], $arr[1]];
                if ($begin) {
                    $where[] = [$key, '>=', ($begin)];
                }
                if ($end) {
                    $where[] = [$key, '<=', ($end)];
                }
                break;
            case 'NOT BETWEEN':
                if (is_array($val)) {
                    $arr = $val;
                    if (count($arr) != 2) {
                        return false;
                    }
                } else {
                    $arr = explode('-', $val);
                    if (count($arr) != 2) {
                        return false;
                    }
                }

                [$begin, $end] = [$arr[0], $arr[1]];
                if ($begin) {
                    $where[] = [$key, '<=', ($begin)];
                }
                if ($end) {
                    $where[] = [$key, '>=', ($end)];
                }
                break;
            case 'RANGE':
//                dump([$val]);

                if (is_array($val)) {
                    $arr = $val;
                    if (count($arr) != 2) {
                        return false;
                    }
                } else {
                    $arr = explode('-', $val);
                    if (count($arr) != 2) {
                        return false;
                    }
                }

                [$begin, $end] = [$arr[0], $arr[1]];


                if ($begin) {
                    $where[] = [$key, '>=', strtotime($begin)];
                }
                if ($end) {

                    if ($begin == $end && stripos($begin, '00:00:00') === false && strtotime($begin) == strtotime($begin . ' 00:00:00')) {
                        $end = $end . ' 23:59:59';
                    }

                    $where[] = [$key, '<=', strtotime($end)];
                }


                break;
            case 'DATERANGE':
                if (is_array($val)) {
                    $arr = $val;
                    if (count($arr) != 2) {
                        return false;
                    }
                } else {
                    $arr = explode('-', $val);
                    if (count($arr) != 2) {
                        return false;
                    }
                }
                [$begin, $end] = [$arr[0], $arr[1]];
                $where[] = [$key, 'BETWEEN TIME', [$begin, $end]];
                break;
            case 'NOT RANGE':
                if (is_array($val)) {
                    $arr = $val;
                    if (count($arr) != 2) {
                        return false;
                    }
                } else {
                    $arr = explode('-', $val);
                    if (count($arr) != 2) {
                        return false;
                    }
                }
                [$begin, $end] = [$arr[0], $arr[1]];
                //当出现一边为空时改变操作符
                if ($begin !== '') {
                    $where[] = [$key, '<=', strtotime($begin)];
                } elseif ($end === '') {
                    $where[] = [$key, '>=', strtotime($begin)];
                }
                break;
            case 'NULL':
            case 'IS NULL':
            case 'NOT NULL':
            case 'IS NOT NULL':
                $where[] = [$key, strtolower(str_replace('IS ', '', $op))];
                break;
            default:
                $where[] = [$key, '=', $val];
        }

        return $where;
    }

    /**
     * 过滤接受的参数
     * @param $str
     * @param bool $filter
     * @return array|mixed|string|string[]
     */
    public function filterWord($str, bool $filter = true)
    {

        if (!$str || !$filter || is_numeric($str)) return $str;
        // 把数据过滤
        $farr = [
//            "/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
//            "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
//            "/select|join|where|drop| like|modify|rename|insert|update|table|database|alter|truncate|\'|\/\*|\.\.\/|\.\/|union| into |load_file|outfile/is",
            /*            "/eval|think|application|\\\x|runtime|wwwroot|shell|phpinfo|phpcode|copy\\(|localhost|vale\\(|assert\\(|<sCrIpt|eval\\(|base64_decode\\(|\\';|\";|\\..\\/..\\/|<.*=(&#\\d+?;?)+?>|<.*data=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\(.*\)|sleep\s*?\(.*\)|\\b(group_)?concat[\\s\\/\\*]*?\\([^\\)]+?\\)|\bcase[\s\/\*]*?when[\s\/\*]*?\([^\)]+?\)|load_file\s*?\\()|<[^>]*?\\b(onerror|onmousemove|onload|onclick|onmouseover)\\b|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/is",*/
            "/eval|\\\x|runtime|wwwroot|phpinfo|phpcode|copy\\(|localhost|vale\\(|assert\\(|<sCrIpt|eval\\(|base64_decode\\(|\\';|\";|\\..\\/..\\/|<.*=(&#\\d+?;?)+?>|<.*data=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\(.*\)|sleep\s*?\(.*\)|\\b(group_)?concat[\\s\\/\\*]*?\\([^\\)]+?\\)|\bcase[\s\/\*]*?when[\s\/\*]*?\([^\)]+?\)|load_file\s*?\\()|<[^>]*?\\b(onerror|onmousemove|onload|onclick|onmouseover)\\b|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)/is",
//            "/卡易信|tozzn.com/isU",
        ];

        //敏感词过滤
        $sensitive = L('SensitiveLogic')->getSensitiveList();

        if (is_array($str)) {
            foreach ($str as &$v) {
                if (is_array($v)) {
                    foreach ($v as &$vv) {
                        if (is_string($vv)) {
                            $vv = preg_replace($farr, '', $vv);
                            $sensitive && $vv = sensitive($vv, $sensitive['name'], $sensitive['value']);
                        }
                    }
                } else {
                    if (is_string($v)) {
                        $v = preg_replace($farr, '', $v);
                        $sensitive && $v = sensitive($v, $sensitive['name'], $sensitive['value']);
                    }
                }
            }
        } else {
            if (is_string($str)) {
                $str = preg_replace($farr, '', $str);
                $sensitive && $str = sensitive($str, $sensitive['name'], $sensitive['value']);
            }
        }
        return $str;
    }

    /**
     * 获取get参数
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */
    public function G(array $params, bool $suffix = true, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    /**
     * 获取post参数
     * @param array $params
     * @param bool $suffix
     * @param bool $filter
     * @return array
     */
    public function P(array $params, bool $suffix = true, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    /**
     * 获取用户访问端
     * @return array|string|null
     */
    public function getFW()
    {
        return $this->header('Form-Where', 'pc');
    }

    /**
     * 获取用户访问客户端
     * @return array|string|null
     */
    public function getKD()
    {
        return $this->header('Kss-Device', '');
    }

    /**
     * 当前访问端
     * @param string $terminal
     * @return bool
     */
    public function isTerminal(string $terminal)
    {
        return strtolower($this->getFW()) === $terminal;
    }

    /**
     * 是否是H5端
     * @return bool
     */
    public function isH5()
    {
        return $this->isTerminal('h5');
    }

    /**
     * 是否是微信端
     * @return bool
     */
    public function isWechat()
    {
        return $this->isTerminal('wechat');
    }

    /**
     * 是否是小程序端
     * @return bool
     */
    public function isRoutine()
    {
        return $this->isTerminal('routine');
    }

    /**
     * 是否是app端
     * @return bool
     */
    public function isApp()
    {
        return $this->isTerminal('app');
    }

    /**
     * 是否是app端
     * @return bool
     */
    public function isPc()
    {
        return $this->isTerminal('pc');
    }

    /**
     * 获取ip
     * @return string
     */
    public function ip(): string
    {
//        file_put_contents('ip1.txt', count(self::$macros) . "\r\n", 8);
//        file_put_contents('ip.txt', $this->server('HTTP_X_FORWARDED_FOR', '') . "\r\n", 8);
        //是否获取过IP
        if ($this->real_ip) {
            return $this->real_ip;
        }


        if ($this->server('HTTP_X_FORWARDED_FOR', '')) {
            $ip = $this->server('HTTP_X_FORWARDED_FOR', '');
        } elseif ($this->server('HTTP_CLIENT_IP', '')) {
            $ip = $this->server('HTTP_CLIENT_IP', '');
        } elseif ($this->server('HTTP_X_REAL_IP', '')) {
            $ip = $this->server('HTTP_X_REAL_IP', '');
        } elseif ($this->server('REMOTE_ADDR', '')) {
            $ip = $this->server('REMOTE_ADDR', '');
        } else {
            $ip = '0.0.0.0';
        }


        //存在转发
        if (stripos($ip, ',') !== false) {
            $ips = array_filter(explode(',', $ip), 'trim');
            //获取cdn IP白名单
//            $cdn_white_list = C('cdn_white_list');//clog会造成无限循环
            $open_cdn = L('ConfigLogic')->getConfig('open_cdn');
            //开启了cdn
            if ($open_cdn) {
                $cdn_white_list = L('ConfigLogic')->getConfig('cdn_white_list');
                //从右往左取IP 防止伪造
//            $ips = array_reverse($ips);
//            $ip = trim($ips[0]);
                //存在cdn白名单 支持区间
                if ($cdn_white_list) {
                    $ips = array_reverse($ips);
                    $ip = trim($ips[0]);

                    foreach ($ips as $req_ip) {
                        $req_ip = trim($req_ip);
                        //不在白名单内
                        if (!L('MemberLogic')->checkWhiteIp($req_ip, $cdn_white_list)) {
                            $ip = $req_ip;
                            break;
                        }
                    }
                } else {
                    //有被伪造风险
                    if (count($ips) == 1) {
                        $ip = $ips[0];
                    } else {
                        $ip = $ips[0] == '127.0.0.1' ? $ips[1] : $ips[0];
                    }
                }
            } else {
                //存在多个 没开cdn就从后面取ip
                $ips = array_reverse($ips);
                $ip = trim($ips[0]);
            }


            //则是IPV6
            if (strlen($ip) > 15) {
                if (!empty($ips[1])) {
                    $ip = trim($ips[1]);
                }
            }
        }

//        file_put_contents('ip.txt', $ip . "\r\n", 8);

        $this->real_ip = $ip;
        return $ip;
    }

    public function host(bool $strict = false): string
    {
        if ($this->host) {
            $host = $this->host;
        } else {
            if (is_test() || config('kss.debug_host')) {
                $host = $this->server('HTTP_HOST');
            } else {
                $host = strval($this->server('HTTP_X_FORWARDED_HOST') ?: $this->server('HTTP_HOST'));
            }
        }

        return true === $strict && strpos($host, ':') ? strstr($host, ':', true) : $host;
    }

    /**
     * 返回正确
     * @param string $msg
     * @param array|null $data
     * @param int $code
     * @param array|null $replace
     * @return \think\Response
     */
    public function success($msg = 'ok', ?array $data = null, $code = 200, ?array $replace = ['code' => 200]): \think\Response
    {
//        if (is_array($msg)) {
//            $data = $msg;
//            $msg = 'ok';
//        }
//        $res = compact('code', 'msg');
//        if (!is_null($data))
//            $res['data'] = $data;
//
//        return \think\Response::create($res, 'json', $replace['code']);
        return success($msg, $data, $code, $replace);
    }

    /**
     * 返回错误
     * @param string $msg
     * @param array|null $data
     * @param array|null $replace
     * @param int $code
     * @return \think\Response
     */
    public function error($msg = 'fail', ?array $data = null, $code = 400, ?array $replace = ['code' => 400])
    {
//        if (is_array($msg)) {
//            $data = $msg;
//            $msg = 'fail';
//        }
//        $res = compact('code', 'msg');
//        !is_null($data) && $res['data'] = $data;
//        return \think\Response::create($res, 'json', $replace['code']);

        return error($msg, $data, $code, $replace);
    }

    public function hasMacro(string $name): bool
    {
        return !!$this->{$name};
    }

    public function __call($method, $parameters)
    {
        if (in_array($method, $this->loginParam)) {
            $result = $this->{$method};
            if (is_callable($result)) {
                return call_user_func_array($result, $parameters);
            } else {
                return $result;
            }
        }
        return null;
    }
}

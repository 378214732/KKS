<?php
// +----------------------------------------------------------------------
// | 卡速售
// +----------------------------------------------------------------------
// | Copyright (c) 2022~2027 https://www.kasushou.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 卡速售
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\validate\admin\v1\sys;

use think\Validate;

class Config extends Validate
{
    protected $regex = [
        'float_two' => '/^[0-9]+(.[0-9]{1,2})?$/',
        'tel' => '/^((\d{3,4}-)?[0-9]{7,8}$)|(^(13[0-9]|14[01456879]|15[0-35-9]|16[2567]|17[0-8]|18[0-9]|19[0-35-9])[0-9]{8}$)$/'
    ];
    /**
     * @var array
     */
    protected $rule = [
        'front_desk_exit' => 'number',
        'back_desk_exit' => 'number',
    ];

    /**
     * @var array
     */
    protected $message = [
        'front_desk_exit.number' => '前台超时时间必需为整数',
        'back_desk_exit.number' => '后台超时时间必需为整数',
    ];


    /**
     * @var array
     */
    protected $scene = [

    ];

}

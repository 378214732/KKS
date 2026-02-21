<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2022~2027 https://www.kasushou.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 卡速售
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace kss\base;

use think\queue\Job;

interface  BaseMiddleware
{
    public function handle(BaseRequest $request, \Closure $next);
}
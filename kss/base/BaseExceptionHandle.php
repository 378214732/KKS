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

use kss\exception\ApiException;
use kss\exception\UploadException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class BaseExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
        ApiException::class,
        UploadException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        //内置日志
        if(config('kss.clog')){
            parent::report($exception);
        }
    }

    /**
     * Render an exception into an HTTP response.
     * @access public
     * @param \think\Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制

        $massageData = [
            'code' => $e->getCode(),
            'msg' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
//            'previous' => $e->getPrevious(),
        ];
        // 添加自定义异常处理机制
        if ($e instanceof HttpResponseException) {
            return parent::render($request, $e);
        } elseif ($e instanceof DbException) {
            if ($massageData) {
                clog($massageData, 'ExceptionError');
            }
            return error('数据错误!', is_test() ? $massageData : null);
        } elseif ($e instanceof ValidateException || $e instanceof UploadException || $e instanceof HttpException) {
            return error($e->getMessage(), null, $e->getCode() ?: 400);
        } elseif ($e instanceof ApiException) {
            if ($massageData) {
                clog($massageData, 'ApiExceptionError');
            }
            return error($e->getMessage(), is_test() ? $massageData : null, $e->getCode() ?: 400);
        }elseif ($e instanceof \RuntimeException) {
            if ($massageData) {
                clog($massageData, 'RuntimeExceptionError');
            }
            return error($e->getMessage(), is_test() ? $massageData : null, $e->getCode() ?: 400);
        } else {
            if ($massageData) {
                clog($massageData, 'ExceptionError');
            }


            return error('未知错误!', is_test() ? $massageData : null, 500);
        }

    }


}

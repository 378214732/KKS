<?php

namespace think\swoole\concerns;

use kss\util\Sys;
use Swoole\Coroutine;
use Swoole\Coroutine\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Status;
use think\App;
use think\Cookie;
use think\Event;
use think\exception\Handle;
use think\facade\Cache;
use think\helper\Arr;
use think\helper\Str;
use think\Http;
use think\Model;
use think\swoole\App as SwooleApp;
use think\swoole\coroutine\Context;
use think\swoole\Http as SwooleHttp;
use think\swoole\response\File as FileResponse;
use Throwable;
use function substr;

/**
 * Trait InteractsWithHttp
 * @package think\swoole\concerns
 * @property App $app
 * @property App $container
 */
trait InteractsWithHttp
{
    use InteractsWithWebsocket, ModifyProperty;

    public function createHttpServer($port = '')
    {
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '运行APP');
        $this->preloadHttp();
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '运行APP');
        $host = $this->getConfig('http.host');

        if ($port == '') {
            $port = $this->getConfig('http.port');
        }

        $options = $this->getConfig('http.options', []);
        try {

            //fonnie
            //去除默认redis连接
            Cache::store('redis')->close();
//            $this->app->cache->store('redis')->close();
        } catch (\Throwable $e) {
//            clog($e, '去除默认redis连接');
        }

        //[
        //            'daemonize'             => false,
        //            'dispatch_mode'         => 1,
        //            'max_request'           => 8000,
        //            'open_tcp_nodelay'      => true,
        //            'reload_async'          => true,
        //            'max_wait_time'         => 60,
        //            'enable_reuse_port'     => true,
        //            'enable_coroutine'      => true,
        //            'http_compression'      => false,
        //            'enable_static_handler' => false,
        //            'buffer_output_size'    => 4 * 1024 * 1024,
        //            'worker_num'            => 4, // Each worker holds a connection pool
        //        ]

//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ], '运行APP');

        $server = new Server($host, $port, false, true);
//        dump($options);
        // $options = array_merge($options, [
        //     'dns_cache_expire' => 0,
        //     'dns_server' => [
        //         '223.5.5.5',
        //         '223.6.6.6',
        //         '8.8.8.8',
        //         '114.114.114.114',
        //     ],
        // ]);
//        clog($options,'http配置');
        $server->set($options);
//        clog([
//            $host,
//            $prot,
//            $options
//        ], '猜测是');

        $server->handle('/', function (Request $req, Response $res) {
//
//            Sys::log([
//                '文件地址' => __FILE__ . ':' . __LINE__,
//                '外部APP的对象标识' => spl_object_id($this->app),
//                '外部APP中的实例个数' => $this->app->count(),
//                '外部APP中的实例' => $this->app->getIterator()->getArrayCopy(),
//            ], '外部APP');


//            try {
//                $ext = pathinfo($req->server['request_uri'], PATHINFO_EXTENSION);
//                //优化图片直接返回
//                if ($ext && !in_array($ext, [
//                        'php',
//                        'html',
//                        'htm'
//                    ])) {
////                    if (!in_array($ext, ['ico', 'png', 'mp4'])) {
////                        file_put_contents('无效的请求地址.json', json_encode([
////                                $req->server,
////                                $req->header,
////                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\r\n", FILE_APPEND);
////                    }
//
////                    $close_req = true;
//                }
//            } catch (\Throwable $e) {
////                file_put_contents('runtime/不存在' . str_replace('/', '_',
////                        $req->server['request_uri'])
////                    . '.json', json_encode([
////                        $e->getMessage(),
////                        $ext,
////                        $req->server,
////                        $req->header,
////                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\r\n", FILE_APPEND);
//            }


            if (empty($close_req)) {

//                try {
//                file_put_contents('runtime/test/' . str_replace('/', '_',
//                        $req->server['request_uri'])
//                    . '.json', json_encode([
//                        1,
//
//                        round(microtime(true) - $req->server['request_time_float'], 3) * 1000,
//                        date('Y-m-d H:i:s') . ' ' . explode('.', round($req->server['request_time_float'], 3) . '')[1],
//                        date('Y-m-d H:i:s') . ' ' . explode('.', round(microtime(true), 3) . '')[1],
//                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\r\n", FILE_APPEND);
//                } catch (Throwable $e) {
//
//                }


                if ($this->wsEnable && $this->isWebsocketRequest($req)) {
                    $this->onHandShake($req, $res);
                } else {
                    $this->onRequest($req, $res);
                }

//                Model::clear();


//                Context::clear();

//                Context::clear();

//                unset($req,$res);


            }


        });


        $server->start();

    }

    protected function preloadHttp()
    {


        //fonnie

//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//            'APP标识' => spl_object_id($this->app)
//        ]);

        $http = $this->app->http;
        $this->app->invokeMethod([$http, 'loadMiddleware'], [], true);

        if ($this->app->config->get('app.with_route', true)) {
            $this->app->invokeMethod([$http, 'loadRoutes'], [], true);
            $route = clone $this->app->route;
            $this->modifyProperty($route, null);
            unset($this->app->route);

            $this->app->resolving(SwooleHttp::class, function ($http, App $app) use ($route) {
                $newRoute = clone $route;
                $this->modifyProperty($newRoute, $app);
                $app->instance('route', $newRoute);
            });
        }

        $middleware = clone $this->app->middleware;
        $this->modifyProperty($middleware, null);
        unset($this->app->middleware);

        $this->app->resolving(SwooleHttp::class, function ($http, App $app) use ($middleware) {
            $newMiddleware = clone $middleware;
            $this->modifyProperty($newMiddleware, $app);
            $app->instance('middleware', $newMiddleware);
        });

        unset($middleware);
        unset($http);
        unset($this->app->http);
        $this->app->bind(Http::class, SwooleHttp::class);
    }

    protected function isWebsocketRequest(Request $req)
    {
        $header = $req->header;
        return strcasecmp(Arr::get($header, 'connection', ''), 'upgrade') === 0 &&
            strcasecmp(Arr::get($header, 'upgrade', ''), 'websocket') === 0;
    }

    protected function prepareHttp($port = '')
    {
        if ($this->getConfig('http.enable', true)) {

            $this->wsEnable = $this->getConfig('websocket.enable', false);

            if ($this->wsEnable) {
                $this->prepareWebsocket();
            }

            $workerNum = $this->getConfig('http.worker_num', swoole_cpu_num());
            try {
                //fonnie
                //去除默认redis连接
                Cache::store('redis')->close();
//                $this->app->cache->store('redis')->close();
            } catch (\Throwable $e) {
//                clog($e, '去除默认redis连接');
            }


            $createHttpServer = function () use ($port) {
                $this->createHttpServer($port);
            };
            $this->addBatchWorker($workerNum, $createHttpServer, 'http server');
        }
    }

    /**
     * "onRequest" listener.
     *
     * @param Request $req
     * @param Response $res
     */
    public function onRequest($req, $res)
    {
//
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ]);

        Coroutine::create(function () use ($req, $res) {
            $this->runInSandbox(function (Http $http, Event $event, SwooleApp $app) use ($req, $res) {

//                try {
//
//                    file_put_contents('runtime/test/' . str_replace('/', '_',
//                            $req->server['request_uri'])
//                        . '.json', json_encode([
//                            2,
//                            round(microtime(true) - $req->server['request_time_float'], 3) * 1000,
//                            date('Y-m-d H:i:s') . ' ' . explode('.', round($req->server['request_time_float'], 3) . '')[1],
//                            date('Y-m-d H:i:s') . ' ' . explode('.', round(microtime(true), 3) . '')[1],
//                        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\r\n", FILE_APPEND);
//
//                } catch (Throwable $e) {
//
//                }
//                Sys::log([
//                    '文件地址' => __FILE__ . ':' . __LINE__,
//                ]);
//                $app->setInConsole(false);
                $request = $this->prepareRequest($req);
//                Sys::log([
//                    '文件地址1111111' => __FILE__ . ':' . __LINE__,
//                ]);

                try {
                    $response = $this->handleRequest($http, $request);

//                    Sys::log([
//                        '文件地址' => __FILE__ . ':' . __LINE__,
//                    ], '请求错误');
                } catch (Throwable $e) {
                    try {
//                        file_put_contents('runtime/test/' . str_replace('/', '_',
//                                $req->server['request_uri'])
//                            . '.json', json_encode([
//                                10001,
//                                $e->getMessage(),
//                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\r\n", FILE_APPEND);
//

                        file_put_contents('runtime/test/' . str_replace('/', '_',
                                $req->server['request_uri'])
                            . '.json', json_encode([
                                10001,
                                $e->getMessage(),
                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\r\n", FILE_APPEND);


                    } catch (Throwable $e1) {

                    }

//                    //清空缓冲区
//                    ob_end_clean();

                    $handle = $app->make(Handle::class);
                    $handle->report($e);
                    $response = $handle->render($request, $e);
                }

//                Sys::log([
//                    '文件地址22222222' => __FILE__ . ':' . __LINE__,
//                ]);

                //用新的cookie替换旧的cookie
//                $cookie = $this->app->make('cookie', [], true);

                $this->setCookie($res, $app->cookie);
                $this->sendResponse($res, $request, $response);

//                $http->getRoutePath()
                $http->end($response);


//                // 手动触发垃圾回收

//                unset($request);
//                unset($response);
//                unset($http);
//                gc_collect_cycles();
//                Sys::log([
//                    '文件地址' => __FILE__ . ':' . __LINE__,
//                ],'请求完毕');

//                Sys::log([
//                    '文件地址' => __FILE__ . ':' . __LINE__,
//                ]);
//                //清除请求
//                unset($request);

//                //清除缓存
//                $this->clearCache();


//                try {
//                    file_put_contents('runtime/test/' . str_replace('/', '_',
//                            $req->server['request_uri'])
//                        . '.json', json_encode([
//                            3,
//                            round(microtime(true) - $req->server['request_time_float'], 3) * 1000,
//                            microtime(true),
//                            $req->server['request_time_float'],
//
//                            date('Y-m-d H:i:s') . ' ' . explode('.', round($req->server['request_time_float'], 3) . '')[1],
//                            date('Y-m-d H:i:s') . ' ' . explode('.', round(microtime(true), 3) . '')[1],
//
//
//                        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\r\n", FILE_APPEND);
//
//                } catch (Throwable $e) {
//
//                }

            });
        });
    }

    protected function handleRequest(Http $http, $request)
    {
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ]);
        $level = ob_get_level();
        ob_start();

        $response = $http->run($request);

//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//        ]);

//        if (ob_get_level() == 0) {
//            ob_start();
//        }

        if (ob_get_length() > 0) {
            $content = $response->getContent();
            $response->content(ob_get_contents() . $content);
        }

//
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//            '返回内容' => $content,
//            '缓冲区级别' => ob_get_level(),
//            '缓冲区长度' => ob_get_length(),
//            '缓冲区内容' => ob_get_contents(),
//
//        ]);
        while (ob_get_level() > $level) {

//            Sys::log([
//                '文件地址end' => __FILE__ . ':' . __LINE__,
//
//            ]);
            ob_end_clean();
        }


        return $response;
    }

    protected function prepareRequest(Request $req)
    {
        $header = $req->header ?: [];
        $server = $req->server ?: [];

        foreach ($header as $key => $value) {
            $server['http_' . str_replace('-', '_', $key)] = $value;
        }

        // 重新实例化请求对象 处理swoole请求数据
        /** @var \think\Request $request */
        $request = $this->app->make('request', [], true);
//        //唯一请求ID
//        $request->reqId = bin2hex(random_bytes(16));
//        Sys::log([
//            '文件地址' => __FILE__ . ':' . __LINE__,
//            '请求ID' => $request->reqId,
//        ]);


        return $request
            ->withHeader($header)
            ->withServer($server)
            ->withGet($req->get ?: [])
            ->withPost($req->post ?: [])
            ->withCookie($req->cookie ?: [])
            ->withFiles($this->getFiles($req))
            ->withInput($req->rawContent())
            ->setBaseUrl($req->server['request_uri'])
            ->setUrl($req->server['request_uri'] . (!empty($req->server['query_string']) ? '?' . $req->server['query_string'] : ''))
            ->setPathinfo(ltrim($req->server['path_info'], '/'));
    }

    protected function getFiles(Request $req)
    {
        if (empty($req->files)) {
            return [];
        }

        return array_map(function ($file) {
            if (!Arr::isAssoc($file)) {
                $files = [];
                foreach ($file as $f) {
                    $files['name'][] = $f['name'];
                    $files['type'][] = $f['type'];
                    $files['tmp_name'][] = $f['tmp_name'];
                    $files['error'][] = $f['error'];
                    $files['size'][] = $f['size'];
                }
                return $files;
            }
            return $file;
        }, $req->files);
    }

    protected function setCookie(Response $res, Cookie $cookie)
    {
        foreach ($cookie->getCookie() as $name => $val) {
            [$value, $expire, $option] = $val;

            $res->cookie($name, $value, $expire, $option['path'], $option['domain'], (bool)$option['secure'], (bool)$option['httponly'], $option['samesite']);
        }
    }

    protected function setHeader(Response $res, array $headers)
    {
        foreach ($headers as $key => $val) {
            $res->header($key, $val);
        }
    }

    protected function setStatus(Response $res, $code)
    {
        $res->status($code, Status::getReasonPhrase($code));
    }

    protected function sendResponse(Response $res, \think\Request $request, \think\Response $response)
    {
        switch (true) {
            case $response instanceof FileResponse:
                $this->sendFile($res, $request, $response);
                break;
            default:
                $this->sendContent($res, $response);
        }
    }

    protected function sendFile(Response $res, \think\Request $request, FileResponse $response)
    {
        $ifNoneMatch = $request->header('If-None-Match');
        $ifRange = $request->header('If-Range');

        $code = $response->getCode();
        $file = $response->getFile();
        $eTag = $response->getHeader('ETag');
        $lastModified = $response->getHeader('Last-Modified');

        $fileSize = $file->getSize();
        $offset = 0;
        $length = -1;

        if ($ifNoneMatch == $eTag) {
            $code = 304;
        } elseif (!$ifRange || $ifRange === $eTag || $ifRange === $lastModified) {
            $range = $request->header('Range', '');
            if (Str::startsWith($range, 'bytes=')) {
                [$start, $end] = explode('-', substr($range, 6), 2) + [0];

                $end = ('' === $end) ? $fileSize - 1 : (int)$end;

                if ('' === $start) {
                    $start = $fileSize - $end;
                    $end = $fileSize - 1;
                } else {
                    $start = (int)$start;
                }

                if ($start <= $end) {
                    $end = min($end, $fileSize - 1);
                    if ($start < 0 || $start > $end) {
                        $code = 416;
                        $response->header([
                            'Content-Range' => sprintf('bytes */%s', $fileSize),
                        ]);
                    } elseif ($end - $start < $fileSize - 1) {
                        $length = $end < $fileSize ? $end - $start + 1 : -1;
                        $offset = $start;
                        $code = 206;
                        $response->header([
                            'Content-Range' => sprintf('bytes %s-%s/%s', $start, $end, $fileSize),
                            'Content-Length' => $end - $start + 1,
                        ]);
                    }
                }
            }
        }

        $this->setStatus($res, $code);
        $this->setHeader($res, $response->getHeader());

        if ($code >= 200 && $code < 300 && $length !== 0) {
            $res->sendfile($file->getPathname(), $offset, $length);
        } else {
            $res->end();
        }
    }

    protected function sendContent(Response $res, \think\Response $response)
    {
        // 由于开启了 Transfer-Encoding: chunked，根据 HTTP 规范，不再需要设置 Content-Length
//        $response->header(['Content-Length' => null]);

        $this->setStatus($res, $response->getCode());
        $this->setHeader($res, $response->getHeader());

        $content = $response->getContent();
        if ($content) {
            $contentSize = strlen($content);
            $chunkSize = 8192;

            if ($contentSize > $chunkSize) {
                $sendSize = 0;
                do {
                    if (!$res->write(substr($content, $sendSize, $chunkSize))) {
                        break;
                    }
                } while (($sendSize += $chunkSize) < $contentSize);
            } else {
                $res->write($content);
            }
            //获取到内容后，手动释放内存
            unset($content);
        }
        $res->end();
    }
}

<?php

namespace kss\base;

use think\facade\Config;

/**
 * Class BaseUpload
 * @package kss\base
 */
class BaseUpload extends BaseStorage
{
    /**
     * 缩略图
     * @var string[]
     */
    protected $thumb = ['big', 'mid', 'small'];

    /**
     * 缩略图配置
     * @var array
     */
    protected $thumbConfig = [
        'thumb_big_height' => 800,
        'thumb_big_width' => 800,
        'thumb_mid_height' => 300,
        'thumb_mid_width' => 300,
        'thumb_small_height' => 150,
        'thumb_small_width' => 150,
    ];
    /**
     * 水印配置
     * @var array
     */
    protected $waterConfig = [
        'image_watermark_status' => 0,
        'watermark_type' => 1,
        'watermark_image' => '',
        'watermark_opacity' => 0,
        'watermark_position' => 1,
        'watermark_rotate' => 0,
        'watermark_text' => '',
        'watermark_text_angle' => "",
        'watermark_text_color' => '#000000',
        'watermark_text_size' => '5',
        'watermark_text_font' => '',
        'watermark_x' => 0,
        'watermark_y' => 0
    ];
    /**
     * 图片信息
     * @var array
     */
    protected $fileInfo;
    /**
     * 下载图片信息
     */
    protected $downFileInfo;

    /**
     * 要生成缩略图、水印的图片地址
     * @var string
     */
    protected $filePath;

    /**
     * 验证配置
     * @var string
     */
    protected $validate;

    /**
     * 保存路径
     * @var string
     */
    protected $path = '';

    /**
     * 是否自动裁剪
     * @var bool
     */
    protected $authThumb = true;

    protected function initialize(array $config)
    {
        $this->fileInfo = $this->downFileInfo = new \StdClass();
        $this->thumbConfig = array_merge($this->thumbConfig, $config['thumb'] ?? []);
        $this->waterConfig = array_merge($this->waterConfig, $config['water'] ?? []);
    }

    /**
     * 设置处理缩略图、水印图片路径
     * @param string $filePath
     * @return $this
     */
    public function setFilepath(string $filePath)
    {
        $this->filePath = substr($filePath, 0, 1) === '.' ? substr($filePath, 1) : $filePath;
        return $this;
    }

    /**
     * 是否自动裁剪
     * @param bool $auth
     * @return $this
     */
    public function setAuthThumb(bool $auth)
    {
        $this->authThumb = $auth;
        return $this;
    }

    /**
     * 上传文件路径
     * @param string $path
     * @return $this
     */
    public function to(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * 获取文件信息
     * @return array
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * 检测是否是图片
     * @param $filePath
     * @return bool
     */
    protected function checkImage($filePath)
    {
        //获取图像信息
        $info = @getimagesize($filePath);
        //检测图像合法性
        if (false === $info || (IMAGETYPE_GIF === $info[2] && empty($info['bits']))) {
            return false;
        }
        return true;
    }

    /**
     * 验证合法上传域名
     * @param string $url
     * @return string
     */
    protected function checkUploadUrl(string $url)
    {
        if ($url && strstr($url, 'http') === false) {
            $url = 'http://' . $url;
        }
        return $url;
    }

    /**
     * 获取系统配置
     * @return mixed
     */
    protected function getConfig()
    {
        try {
            $this->configFile = 'filesystem';
            $config = Config::get($this->configFile . '.stores.' . $this->name, []);
            if (empty($config)) {
                $config['filesize'] = Config::get($this->configFile . '.filesize', []);
                $config['fileExt'] = Config::get($this->configFile . '.fileExt', []);
                $config['fileMime'] = Config::get($this->configFile . '.fileMime', []);
            }
        } catch (\Throwable $e) {
            $config = [
                //上传文件大小
                'filesize' => 2097152,
                //上传文件后缀类型
                'fileExt' => ['jpg', 'jpeg', 'png', 'gif', 'pem', 'mp3', 'wma', 'wav', 'amr', 'mp4', 'key', 'xlsx', 'xls', 'video/mp4'],
                //上传文件类型
                'fileMime' => [
                    'image/jpeg',
                    'image/gif',
                    'image/png',
                    'text/plain',
                    'audio/mpeg',
                    'application/octet-stream',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-works',
                    'application/vnd.ms-excel',
                    'application/zip',
                    'application/vnd.ms-excel',
                    'application/vnd.ms-excel',
                    'text/xml',
                ],
            ];
        }

        return $config;
    }

    /**
     * 设置验证规则
     * @param array|null $validate
     * @return $this
     */
    public function validate(?array $validate = null)
    {
        if (is_null($validate)) {
            $validate = $this->getConfig();
        }
        $this->extractValidate($validate);
        return $this;
    }

    /**
     * 验证目录是否正确
     * @param string $key
     * @return false|string
     */
    protected function getUploadPath(string $key)
    {
        $path = ($this->path ? $this->path . '/' : '') . $key;
        if ($path && $path[0] === '/') {
            $path = substr($path, 1);
        }
        return $path;
    }

    /**
     * 提取上传验证
     */
    protected function extractValidate(array $validateArray)
    {
        $validate = [];
        foreach ($validateArray as $key => $value) {
            $validate[] = $key . ':' . (is_array($value) ? implode(',', $value) : $value);
        }
        $this->validate = implode('|', $validate);
        unset($validate);
    }

    /**
     * 提取文件名
     * @param string $path
     * @param string $ext
     * @return string
     */
    protected function saveFileName(string $path = null, string $ext = 'jpg')
    {
        mt_srand();
        return ($path ? substr(md5($path), 0, 5) : '') . date('YmdHis') . rand(0, 9999) . '.' . $ext;
    }

    /**
     * 提取文件后缀以及之前部分
     * @param string $path
     * @return false|string[]
     */
    protected function getFileName(string $path)
    {
        $_empty = ['', ''];
        if (!$path) return $_empty;
        if (strpos($path, '?')) {
            $_tarr = explode('?', $path);
            $path = trim($_tarr[0]);
        }
        $arr = explode('.', $path);
        if (!is_array($arr) || count($arr) <= 1) return $_empty;
        $ext_name = trim($arr[count($arr) - 1]);
        $ext_name = !$ext_name ? 'jpg' : $ext_name;
        return [explode('.' . $ext_name, $path)[0], $ext_name];
    }

    /**
     * 获取图片地址
     * @param string $filePath
     * @param bool $is_parse_url
     * @return string
     */
    protected function getFilePath(string $filePath = '', bool $is_parse_url = false)
    {
        $path = $filePath ? $filePath : $this->filePath;
        if ($is_parse_url) {
            $data = parse_url($path);
            //远程地址处理
            if (isset($data['host']) && isset($data['path'])) {
                if (file_exists(app()->getRootPath() . 'public' . $data['path'])) {
                    $path = $data['path'];
                }
            }
        }
        return $path;
    }

    /**
     * 文件是否在本地
     * @param string $filePath
     * @return bool
     */
    protected function isLocal(string $filePath)
    {
        $isLocal = false;
        $path = $filePath;
        $data = parse_url($path);
        //远程地址处理
        if (isset($data['host']) && isset($data['path'])) {
            if (file_exists(app()->getRootPath() . 'public' . $data['path'])) {
                $isLocal = true;
            }
        }

        return $isLocal;
    }

    /**
     * 获取文件类型和大小
     * @param string $url
     * @param bool $isData
     * @return array
     */
    protected function getFileHeaders(string $url, $isData = true)
    {
        stream_context_set_default(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
        $header['size'] = 0;
        $header['type'] = 'image/jpeg';

        if (!$isData) {
            return $header;
        }
        try {
            $headerArray = get_headers(str_replace('\\', '/', $url), true);
            if (!isset($headerArray['Content-Length'])) {
                $header['size'] = 0;
            } else {
                $header['size'] = $headerArray['Content-Length'];
            }
            if (!isset($headerArray['Content-Type'])) {
                $header['type'] = 'image/jpeg';
            } else {
                $header['type'] = $headerArray['Content-Type'];
            }
            if (is_array($headerArray['Content-Length']) && count($headerArray['Content-Length']) == 2) {
                $header['size'] = $headerArray['Content-Length'][1];
            }
            if (is_array($headerArray['Content-Type']) && count($headerArray['Content-Type']) == 2) {
                $header['type'] = $headerArray['Content-Type'][1];
            }
        } catch (\Exception $e) {
        }
        return $header;
    }

    /**
     * 获取上传信息
     * @return array
     */
    public function getUploadInfo()
    {
        if (isset($this->fileInfo->filePath)) {
            if (strstr($this->fileInfo->filePath, 'http') === false) {
                $url = request()->domain() . $this->fileInfo->filePath;
            } else {
                $url = $this->fileInfo->filePath;
            }
            $headers = $this->getFileHeaders($url);
//        clog($headers,'aba');
            return [
                'name' => $this->fileInfo->fileName,
                'real_name' => $this->fileInfo->realName ?? '',
                'delete_url' => $this->fileInfo->deleteUrl ?? '',
                'size' => $headers['size'] ?? 0,
                'type' => $headers['type'] ?? 'image/jpeg',
                'dir' => $this->fileInfo->filePath,
                'thumb_path' => $this->fileInfo->filePath,
                'thumb_path_big' => $this->fileInfo->filePathBig ?? '',
                'thumb_path_mid' => $this->fileInfo->filePathMid ?? '',
                'thumb_path_small' => $this->fileInfo->filePathSmall ?? '',
                'thumb_path_water' => $this->fileInfo->filePathWater ?? '',
                'time' => time(),
                'md5' => $this->fileInfo->md5 ?? '',
            ];
        } else {
            return [];
        }
    }

    /**
     * 获取下载信息
     * @return array
     */
    public function getDownloadInfo()
    {
        if (isset($this->downFileInfo->downloadFilePath)) {
            if (strstr($this->downFileInfo->downloadFilePath, 'http') === false) {
                $url = request()->domain() . $this->downFileInfo->downloadFilePath;
            } else {
                $url = $this->downFileInfo->downloadFilePath;
            }
            $headers = $this->getFileHeaders($url);
            return [
                'name' => $this->downFileInfo->downloadFileName,
                'real_name' => $this->downFileInfo->downloadRealName ?? '',
                'size' => $headers['size'] ?? 0,
                'type' => $headers['type'] ?? 'image/jpeg',
                'dir' => $this->downFileInfo->downloadFilePath ?? '',
                'thumb_path' => $this->downFileInfo->downloadFilePath ?? '',
                'time' => time(),
            ];
        } else {
            return [];
        }
    }

    /**
     * 文件上传
     * @return mixed
     */
    public function move(string $file = 'file')
    {
        return false;
    }

    /**
     * 文件流上传
     * @return mixed
     */
    public function stream(string $fileContent, string $key = null)
    {
        return false;
    }

    /**
     * 删除文件
     * @return mixed
     */
    public function delete(string $filePath)
    {
        return false;
    }

    /**
     * 实例化上传
     * @return mixed
     */
    protected function app()
    {
        return false;
    }

    /**
     * 获取上传密钥
     * @return mixed
     */
    public function getTempKeys()
    {
        return false;
    }

    /**
     * 获取缩略图
     * @return mixed
     */
    public function thumb(string $filePath = '')
    {
        return false;
    }

    /**
     * 添加水印
     * @return mixed
     */
    public function water(string $filePath = '')
    {
        return false;
    }

    /**
     * 获取文件md5
     * @param string $fileContent
     * @return false|string
     */
    public function getFileMd5(string $fileContent = '')
    {
        $md5 = '';
        if ($fileContent) {
            if (is_file($fileContent)) {
                $md5 = md5_file($fileContent);
            } else {
                if (stripos($fileContent, 'http') === 0 || strlen($fileContent) < 500) {
                    clog($fileContent,'获取文件内容');
                    $fileContent = file_get_contents($fileContent);
                }
                if($fileContent){
                    //获取文件md5 重复不保存
                    $tempFilePath = tempnam(sys_get_temp_dir(), 'php'); // 创建一个临时文件
                    file_put_contents($tempFilePath, $fileContent); // 将二进制数据写入临时文件
                    $md5 = md5_file($tempFilePath); // 获取MD5值
                    unlink($tempFilePath); // 删除临时文件
                }

            }
        }
        return $md5;
    }

}

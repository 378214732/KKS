<?php
declare(strict_types=1);

namespace Fastknife\Service;

use app\common\job\sys\AjCaptcha;
use Fastknife\Domain\Vo\PointVo;
use Fastknife\Utils\RandomUtils;
use kss\util\Cache;

class BlockPuzzleCaptchaService extends Service
{
    /**
     * 获取验证码图片信息
     * @return array
     */
//    public function get(): array
//    {
//        $host = request()->host();
//        $cacheEntity = $this->factory->getCacheInstance();
//        $blockImage = $this->factory->makeBlockImage();
//        //画小图和干扰图
//        $blockImage->run();
//        $data = [
//            'originalImageBase64' => $blockImage->response(),
//            'jigsawImageBase64' => $blockImage->response('template'),
//            'secretKey' => RandomUtils::getRandomCode(16, 3),
//            'token' => RandomUtils::getUUID(),
//        ];
//        if ($host == 'new.ezhancn.com') {
//            $data['point'] = $blockImage->getPoint();
////                clog($data, '生成滑块验证码');
//        }
//        $cacheEntity->set($data['token'], [
//            'secretKey' => $data['secretKey'],
//            'point' => $blockImage->getPoint(),
//        ], $this->factory->getConfig()['cache']['options']['expire'] ?? 300);
//        return $data;
//    }

    /**
     * 获取验证码图片信息
     * @return array
     */
    public function get(): array
    {
        $key = 'aj_captcha';
        $num = Cache::lLenData($key);

        if ($num > 0) {
            $captchaData = Cache::popData($key);
        }

        if ($num < 2) {
            AjCaptcha::push(['blockPuzzle']);
        }

        if (empty($captchaData)) {
            $this->create(1, request()->host());
        }

        $captchaData = Cache::popData($key);

        //缓存
        $data = $captchaData['d'];
        $cacheEntity = $this->factory->getCacheInstance();
        $cacheEntity->set($data['token'], [
            'secretKey' => $data['secretKey'],
            'point' => $captchaData['p'],
        ], $this->factory->getConfig()['cache']['options']['expire'] ?? 300);
        return $data;
    }

    public function create($num = 1, $host = '')
    {
        $key = 'aj_captcha';
        for ($i = 0; $i < $num; $i++) {
            $blockImage = $this->factory->makeBlockImage();
            //画小图和干扰图
            $blockImage->run();
            $data = [
                'originalImageBase64' => $blockImage->response(),
                'jigsawImageBase64' => $blockImage->response('template'),
                'secretKey' => RandomUtils::getRandomCode(16, 3),
                'token' => RandomUtils::getUUID(),
            ];
            if ($host == 'new.ezhancn.com') {
                $data['point'] = $blockImage->getPoint();
            }
            //加入协程
            $captchaData = [
                'd' => $data,
                'p' => $blockImage->getPoint(),
            ];
            Cache::pushData($key, $captchaData);
        }
    }

    /**
     * 验证
     * @param string $token
     * @param string $pointJson
     */
    public function validate($token, $pointJson)
    {
        //获取并设置 $this->originData
        $this->setOriginData($token);

        //数据处理类
        $blockData = $this->factory->makeBlockData();

        //解码出来的前端坐标
        $targetPoint = $this->encodePoint($this->originData['secretKey'], $pointJson);;
        $targetPoint = new PointVo($targetPoint['x'], $targetPoint['y']);

        //检查
        $blockData->check($this->originData['point'], $targetPoint);
    }
}

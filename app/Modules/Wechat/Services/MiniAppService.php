<?php

declare(strict_types=1);

namespace App\Modules\Wechat\Services;

use App\Exceptions\CustomException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\HttpClient\AccessTokenAwareClient;
use EasyWeChat\MiniApp\Application;
use EasyWeChat\MiniApp\Utils;
use Illuminate\Support\Facades\Cache;
use Throwable;

class MiniAppService extends BaseService
{
    private ?Application $app;

    /**
     * 设置小程序配置
     *
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $config = config('wechat.mini_app');

        $this->app = new Application($config);
        $this->app->setCache(Cache::store());
    }

    public function getApp(): Application
    {
        return $this->app;
    }

    public function getClient(): AccessTokenAwareClient
    {
        return $this->getApp()->getClient();
    }

    public function getUtils(): Utils
    {
        return $this->getApp()->getUtils();
    }

    /**
     * 通过 access_token 换取微信手机号
     *
     * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/user-info/phone-number/getPhoneNumber.html
     *
     * @throws CustomException
     */
    public function getPhoneNumber(string $code): array
    {
        try {
            $result = $this->app->getClient()->postJson('/wxa/business/getuserphonenumber', [
                'code' => $code,
            ])->toArray();

            if ($result['errcode'] === 0) {
                return $result['phone_info'];
            }

            throw new CustomException('手机号码获取失败:'.$result['errmsg']);
        } catch (Throwable $e) {
            throw new CustomException($e->getMessage());
        }
    }

    /**
     * @throws CustomException
     */
    public function getUnlimitedQRCode(string $scene, string $path, int $width = 430, bool $checkPath = false): string
    {
        try {
            $response = $this->app->getClient()->postJson('/wxa/getwxacodeunlimit', [
                'scene' => $scene,
                'page' => $path,
                'width' => $width,
                'check_path' => $checkPath,
            ]);

            return $response->toDataUrl();
        } catch (\Throwable $e) {
            throw new CustomException($e->getMessage());
        }
    }
}

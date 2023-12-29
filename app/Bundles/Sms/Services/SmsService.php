<?php

declare(strict_types=1);

namespace App\Bundles\Sms\Services;

use App\Foundation\Exceptions\CustomException;
use Exception;
use Illuminate\Support\Facades\Cache;

class SmsService
{
    /**
     * 短信模块缓存前缀
     */
    const CACHE_PREFIX = 'sms_';

    /**
     * 短信缓存有效时间
     */
    const CACHE_EXPIRE = 10 * 60;

    /**
     * @throws Exception
     */
    public function send(string $mobile, string $template, array $data): array
    {
        $templates = config('services.easy-sms.templates');
        if (!isset($templates[$template])) {
            throw new Exception('没有找到短信模板');
        }

        $smsKey = array_key_first($templates[$template]);
        $content = $templates[$template][$smsKey];

        return app('easy-sms')->send($mobile, [
            'content' => $this->contentParser($content, $data),
            'template' => $smsKey,
            'data' => $data,
        ]);
    }

    /**
     * 发送短信验证码
     *
     * @throws Exception
     */
    public function sendCode(string $mobile): void
    {
        $code = mt_rand(100000, 999999);

        Cache::put(self::CACHE_PREFIX . $mobile, $code, self::CACHE_EXPIRE);

        $this->send($mobile, 'SMS_CODE', ['code' => $code]);
    }

    /**
     * 校验短信验证码
     */
    public function checkCode(string $mobile, string $code): bool
    {
        $smsCode = Cache::get(self::CACHE_PREFIX . $mobile);

        return $smsCode === $code;
    }

    /**
     * 短信内容模板解析
     */
    private function contentParser(string $content, array $data): string
    {
        // 替换消息变量
        preg_match_all('/\$\{(.+?)\\\}/', $content, $matches);
        foreach ($matches[1] as $vo) {
            $content = str_replace('${' . $vo . '}', $data[$vo], $content);
        }

        return $content;
    }
}

<?php

declare(strict_types=1);

namespace Juling\Sms\Services;

use Exception;

class SmsBundleService
{
    /**
     * @throws Exception
     */
    public function send(string $mobile, string $template, array $data): array
    {
        $templates = config('services.easy-sms.templates');
        if (! isset($templates[$template])) {
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
     * 短信内容模板解析
     */
    private function contentParser(string $content, array $data): string
    {
        // 替换消息变量
        preg_match_all('/\$\{(.+?)\\\}/', $content, $matches);
        foreach ($matches[1] as $vo) {
            $content = str_replace('${'.$vo.'}', $data[$vo], $content);
        }

        return $content;
    }
}

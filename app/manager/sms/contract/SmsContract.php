<?php

declare(strict_types=1);

namespace app\manager\sms\contract;

/**
 * Class SmsContract
 * @package app\manager\sms\contract
 */
interface SmsContract
{
    /**
     * 短信发送
     * @param $mobile
     * @param $content
     * @return bool
     */
    public function send($mobile, $content): bool;

    /**
     * 查询账户余额
     * @return array
     */
    public function balance(): array;

    /**
     * 查询发送历史
     * @return array
     */
    public function history(): array;
}

<?php

declare(strict_types=1);

namespace App\Contracts;

interface CodeEnumInterface
{
    /**
     * 获取枚举名
     */
    public function getName(): string;

    /**
     * 获取枚举值
     */
    public function getValue(): int;

    /**
     * 获取枚举描述
     */
    public function getDescription(): string;
}

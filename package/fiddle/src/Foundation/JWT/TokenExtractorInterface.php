<?php

declare(strict_types=1);

namespace App\Foundation\JWT;

interface TokenExtractorInterface
{
    /**
     * 提取token
     */
    public function extractToken(): string;
}

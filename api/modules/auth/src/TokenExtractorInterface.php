<?php

declare(strict_types=1);

namespace Juling\Auth;

interface TokenExtractorInterface
{
    /**
     * 提取token
     */
    public function extractToken(): string;
}

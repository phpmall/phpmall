<?php

declare(strict_types=1);

namespace App\Foundation\JWT;

use App\Foundation\JWT\TokenExtractorInterface;
use App\Foundation\JWT\Exception\ExtractTokenException;

class BearerTokenExtractor implements TokenExtractorInterface
{
    /**
     * 提取token
     *
     * @throws ExtractTokenException
     */
    public function extractToken(): string
    {
        $header = request()->header('Authorization', '');

        if (is_string($header)) {
            $position = strrpos($header, 'Bearer ');

            if ($position !== false) {
                $header = substr($header, $position + 7);

                return str_contains($header, ',') ? strstr($header, ',', true) : $header;
            }
        }

        throw new ExtractTokenException('Authorization token cannot be empty');
    }
}

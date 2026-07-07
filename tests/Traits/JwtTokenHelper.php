<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Modules\User\Models\User;
use Illuminate\Support\Str;
use Juling\Auth\Authentication;

trait JwtTokenHelper
{
    private function generateJwtToken(User $user, string $type = 'user'): string
    {
        $auth = new Authentication;
        $now = now()->timestamp;
        $ttl = (int) config('jwt.ttl', 120) * 60;
        $jti = (string) Str::uuid();

        $payload = [
            'iss' => config('jwt.payload.iss', config('app.url')),
            'aud' => config('jwt.payload.aud', config('app.url')),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $ttl,
            'sub' => $user->id,
            'type' => $type,
            'jti' => $jti,
            'merchant_id' => null,
            'refreshable_until' => $now + $ttl,
        ];

        return $auth->createToken($payload);
    }
}

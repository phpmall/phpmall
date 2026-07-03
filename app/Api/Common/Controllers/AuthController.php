<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\Auth\RefreshRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Juling\Auth\Authentication;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    private Authentication $auth;

    public function __construct()
    {
        $this->auth = new Authentication;
    }

    #[OA\Post(path: '/common/v1/auth/refresh', summary: '刷新 Token', security: [[]], tags: ['公共认证'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RefreshRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'code', type: 'integer'),
        new OA\Property(property: 'data', properties: [
            new OA\Property(property: 'access_token', type: 'string'),
            new OA\Property(property: 'refresh_token', type: 'string'),
            new OA\Property(property: 'expires_in', type: 'integer'),
            new OA\Property(property: 'token_type', type: 'string'),
        ]),
    ]))]
    public function refresh(RefreshRequest $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Missing token',
                'data' => null,
            ], 401);
        }

        try {
            $payload = $this->auth->getPayloadByToken($token);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid token',
                'data' => null,
            ], 401);
        }

        // 检查黑名单
        if (! empty($payload['jti'])) {
            $blacklisted = Redis::connection()->get('jwt:blacklist:'.$payload['jti']);
            if ($blacklisted) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Token revoked',
                    'data' => null,
                ], 401);
            }
        }

        // 将旧 token 的 jti 加入黑名单 24 小时
        if (! empty($payload['jti'])) {
            Redis::connection()->setex('jwt:blacklist:'.$payload['jti'], 86400, '1');
        }

        $sub = (int) $payload['sub'];
        $type = $payload['type'] ?? 'user';
        $merchantId = $payload['merchant_id'] ?? null;

        $tokens = $this->generateTokens($sub, $type, $merchantId);

        return response()->json([
            'code' => 0,
            'data' => [
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'expires_in' => $tokens['expires_in'],
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * 生成 access_token 和 refresh_token
     */
    private function generateTokens(int $sub, string $type, ?int $merchantId = null): array
    {
        $now = now()->timestamp;
        $ttl = (int) config('jwt.ttl', 120) * 60;
        $refreshTtl = (int) config('jwt.refresh_ttl', 10080) * 60;
        $jti = (string) Str::uuid();
        $refreshJti = (string) Str::uuid();

        $accessPayload = [
            'iss' => config('jwt.payload.iss', config('app.url')),
            'aud' => config('jwt.payload.aud', config('app.url')),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $ttl,
            'sub' => $sub,
            'type' => $type,
            'jti' => $jti,
            'merchant_id' => $merchantId,
        ];

        $refreshPayload = [
            'iss' => config('jwt.payload.iss', config('app.url')),
            'aud' => config('jwt.payload.aud', config('app.url')),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $refreshTtl,
            'sub' => $sub,
            'type' => $type,
            'jti' => $refreshJti,
            'token_type' => 'refresh',
            'merchant_id' => $merchantId,
        ];

        return [
            'access_token' => $this->auth->createToken($accessPayload),
            'refresh_token' => $this->auth->createToken($refreshPayload),
            'expires_in' => $ttl,
        ];
    }
}

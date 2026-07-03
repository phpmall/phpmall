<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Modules\User\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Juling\Auth\Authentication;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return $this->unauthorized('Unauthorized: missing token');
        }

        $auth = new Authentication;

        try {
            $payload = $auth->getPayloadByToken($token);
        } catch (\Throwable $e) {
            return $this->unauthorized('Unauthorized: invalid token');
        }

        // 检查黑名单
        if (! empty($payload['jti'])) {
            $blacklisted = Redis::connection()->get('jwt:blacklist:'.$payload['jti']);
            if ($blacklisted) {
                return $this->unauthorized('Unauthorized: token revoked');
            }
        }

        // 将解析后的 payload 注入请求
        $request->attributes->set('auth_payload', $payload);
        $request->attributes->set('jwt_payload', $payload);
        $request->attributes->set('jwt_sub', $payload['sub'] ?? null);
        $request->attributes->set('jwt_type', $payload['type'] ?? null);
        $request->attributes->set('jwt_merchant_id', $payload['merchant_id'] ?? null);
        $request->attributes->set('jwt_jti', $payload['jti'] ?? null);

        // 尝试设置认证用户
        if (! empty($payload['sub'])) {
            $user = User::find($payload['sub']);
            if ($user) {
                $request->setUserResolver(fn () => $user);
                $request->attributes->set('auth_user', $user);
            }
        }

        return $next($request);
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json([
            'code' => 401,
            'message' => $message,
            'data' => null,
        ], 401);
    }
}

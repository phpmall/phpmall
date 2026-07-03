<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Auth\ForgotPasswordRequest;
use App\Api\User\Requests\Auth\LoginRequest;
use App\Api\User\Requests\Auth\LogoutRequest;
use App\Api\User\Requests\Auth\ResetPasswordRequest;
use App\Api\User\Requests\Auth\SignupRequest;
use App\Api\User\Responses\Auth\ForgotPasswordResponse;
use App\Api\User\Responses\Auth\LoginResponse;
use App\Api\User\Responses\Auth\LogoutResponse;
use App\Api\User\Responses\Auth\ResetPasswordResponse;
use App\Api\User\Responses\Auth\SignupResponse;
use App\Http\Controllers\Controller;
use App\Modules\User\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Juling\Auth\Authentication;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    private Authentication $auth;

    public function __construct()
    {
        $this->auth = new Authentication;
    }

    #[OA\Post(path: '/auth/signup', summary: '新会员注册', security: [[]], tags: ['会员认证'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SignupRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SignupResponse::class))]
    public function signup(SignupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['mobile'],
            'phone' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        $tokens = $this->generateTokens($user->id, 'user');

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

    #[OA\Post(path: '/auth/login', summary: '会员登录接口', security: [[]], tags: ['会员认证'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::where('phone', $validated['mobile'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                LoginRequest::getMobile => trans('auth.failed'),
            ]);
        }

        $tokens = $this->generateTokens($user->id, 'user');

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

    #[OA\Post(path: '/auth/forgot-password', summary: '忘记密码', security: [[]], tags: ['会员认证'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ForgotPasswordRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ForgotPasswordResponse::class))]
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->string('password')->toString()),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }

    #[OA\Post(path: '/auth/reset-password', summary: '重置密码', security: [[]], tags: ['会员认证'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ResetPasswordRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ResetPasswordResponse::class))]
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }

    #[OA\Post(path: '/auth/logout', summary: '会员登出', security: [['bearerAuth' => []]], tags: ['会员认证'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LogoutRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LogoutResponse::class))]
    public function logout(LogoutRequest $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token) {
            try {
                $payload = $this->auth->getPayloadByToken($token);
                if (! empty($payload['jti'])) {
                    $ttl = (int) config('jwt.ttl', 120) * 60;
                    Redis::connection()->setex('jwt:blacklist:'.$payload['jti'], $ttl, '1');
                }
            } catch (\Throwable) {
                // 即使解析失败也返回成功
            }
        }

        return response()->json([
            'code' => 0,
            'message' => '登出成功',
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

<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Auth;

use App\Enums\RoleEnum;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: LoginRequest::class,
    required: [
        self::getUsername,
        self::getPassword,
        self::getCaptcha,
        self::getCaptchaId,
    ],
    properties: [
        new OA\Property(property: self::getUsername, description: '用户用户名', type: 'string'),
        new OA\Property(property: self::getPassword, description: '用户登录密码', type: 'string'),
        new OA\Property(property: self::getCaptcha, description: '登录图片验证码', type: 'string'),
        new OA\Property(property: self::getCaptchaId, description: '图片验证码ID', type: 'string'),

    ]
)]
class LoginRequest extends FormRequest
{
    const string getUsername = 'user_name';

    const string getPassword = 'password';

    const string getCaptcha = 'captcha';

    const string getCaptchaId = 'captchaId';

    public function rules(): array
    {
        return [
            self::getUsername => ['required'],
            self::getPassword => 'required',
            self::getCaptcha => 'required',
            self::getCaptchaId => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUsername.'.required' => '请填写用户名',
            self::getPassword.'.required' => '请填写登录密码',
            self::getCaptcha.'.required' => '请填写图片验证码',
            self::getCaptchaId.'.required' => '请填写图片验证码ID',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::guard(RoleEnum::Admin->value)->attempt($this->only(self::getUsername, self::getPassword), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                self::getUsername => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            self::getUsername => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower(RoleEnum::Admin->value.'|'.$this->string(self::getUsername)).'|'.$this->ip());
    }
}

<?php

declare(strict_types=1);

namespace App\Api\Supplier\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Juling\Foundation\Rules\PhoneNumberRule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierLoginRequest',
    required: [
        self::getMobile,
        self::getPassword,
        self::getCaptcha,
        self::getUUID,
        self::getDeviceName,
    ],
    properties: [
        new OA\Property(property: self::getMobile, description: '用户手机号', type: 'string'),
        new OA\Property(property: self::getPassword, description: '用户登录密码', type: 'string'),
        new OA\Property(property: self::getCaptcha, description: '登录图片验证码', type: 'string'),
        new OA\Property(property: self::getUUID, description: '图片验证码ID', type: 'string'),
        new OA\Property(property: self::getDeviceName, description: '设备名称', type: 'string'),
    ]
)]
class LoginRequest extends FormRequest
{
    const string getMobile = 'mobile';

    const string getPassword = 'password';

    const string getCaptcha = 'captcha';

    const string getUUID = 'uuid';

    const string getDeviceName = 'device_name';

    public function rules(): array
    {
        return [
            self::getMobile => ['required', 'string', new PhoneNumberRule],
            self::getPassword => 'required',
            self::getCaptcha => 'required',
            self::getUUID => 'required',
            self::getDeviceName => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getMobile.'.required' => '请填写手机号码',
            self::getPassword.'.required' => '请填写登录密码',
            self::getCaptcha.'.required' => '请填写图片验证码',
            self::getUUID.'.required' => '请填写图片验证码ID',
            self::getDeviceName.'.required' => '请填写设备信息',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(self::getMobile, self::getPassword), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                self::getMobile => trans('auth.failed'),
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
            self::getMobile => trans('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->string(self::getMobile)).'|'.$this->ip());
    }
}

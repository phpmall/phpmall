<?php

declare(strict_types=1);

namespace Juling\Captcha\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Juling\Captcha\Captcha;

class CaptchaRule implements DataAwareRule, ValidationRule
{
    /**
     * 正在验证的所有数据。
     */
    protected array $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $captcha = new Captcha();
        if (! $captcha->check($this->data['uuid'], $value)) {
            $fail('图片验证码不正确');
        }
    }

    /**
     * 设置正在验证的数据
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}

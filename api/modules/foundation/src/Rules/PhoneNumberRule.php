<?php

declare(strict_types=1);

namespace Juling\Foundation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_mobile($value)) {
            $fail('手机号码格式不正确');
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Gateways\Auth\Services\Input;

class UserRegisterInput
{
    private string $mobile;

    private string $code;

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}

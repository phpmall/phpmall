<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Services\Input;

class LoginInput
{
    private string $username;

    private string $password;

    private string $captcha;

    private string $uuid;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCaptcha(): string
    {
        return $this->captcha;
    }

    public function setCaptcha(string $captcha): void
    {
        $this->captcha = $captcha;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }
}

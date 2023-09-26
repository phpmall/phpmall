<?php

declare(strict_types=1);

namespace App\Bundles\User\Services\Output;

class UserModel
{
    /**
     * 用户ID
     */
    private int $id;

    /**
     * 用户名
     */
    private string $username;

    /**
     * 用户登录密码
     */
    private string $password;

    /**
     * 登录密码盐值
     */
    private string $password_salt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

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

    public function getPasswordSalt(): string
    {
        return $this->password_salt;
    }

    public function setPasswordSalt(string $password_salt): void
    {
        $this->password_salt = $password_salt;
    }
}

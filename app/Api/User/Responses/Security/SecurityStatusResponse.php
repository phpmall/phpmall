<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Security;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SecurityStatusResponse')]
class SecurityStatusResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'phone_bound', description: '是否绑定手机:0否，1是', type: 'integer')]
    private int $phoneBound;

    #[OA\Property(property: 'email_bound', description: '是否绑定邮箱:0否，1是', type: 'integer')]
    private int $emailBound;

    #[OA\Property(property: 'real_name_verified', description: '是否实名认证:0否，1是', type: 'integer')]
    private int $realNameVerified;

    #[OA\Property(property: 'password_set', description: '是否设置密码:0否，1是', type: 'integer')]
    private int $passwordSet;

    #[OA\Property(property: 'last_login_at', description: '最后登录时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $lastLoginAt;

    #[OA\Property(property: 'last_login_ip', description: '最后登录IP', type: 'string', nullable: true)]
    private ?string $lastLoginIp;

    #[OA\Property(property: 'security_level', description: '安全等级:low,medium,high', type: 'string')]
    private string $securityLevel;

    public function getPhoneBound(): int
    {
        return $this->phoneBound;
    }

    public function setPhoneBound(int $phoneBound): void
    {
        $this->phoneBound = $phoneBound;
    }

    public function getEmailBound(): int
    {
        return $this->emailBound;
    }

    public function setEmailBound(int $emailBound): void
    {
        $this->emailBound = $emailBound;
    }

    public function getRealNameVerified(): int
    {
        return $this->realNameVerified;
    }

    public function setRealNameVerified(int $realNameVerified): void
    {
        $this->realNameVerified = $realNameVerified;
    }

    public function getPasswordSet(): int
    {
        return $this->passwordSet;
    }

    public function setPasswordSet(int $passwordSet): void
    {
        $this->passwordSet = $passwordSet;
    }

    public function getLastLoginAt(): ?string
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?string $lastLoginAt): void
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    public function getLastLoginIp(): ?string
    {
        return $this->lastLoginIp;
    }

    public function setLastLoginIp(?string $lastLoginIp): void
    {
        $this->lastLoginIp = $lastLoginIp;
    }

    public function getSecurityLevel(): string
    {
        return $this->securityLevel;
    }

    public function setSecurityLevel(string $securityLevel): void
    {
        $this->securityLevel = $securityLevel;
    }
}

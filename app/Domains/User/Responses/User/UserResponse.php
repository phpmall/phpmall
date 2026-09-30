<?php

declare(strict_types=1);

namespace App\Domains\User\Responses\User;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserResponse')]
class UserResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '用户ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '用户名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'email', description: '邮箱账号', type: 'string')]
    private string $email;

    #[OA\Property(property: 'emailVerifiedAt', description: '邮箱验证时间', type: 'string')]
    private string $emailVerifiedAt;

    #[OA\Property(property: 'rememberToken', description: '记住我Token', type: 'string')]
    private string $rememberToken;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    /**
     * 获取用户ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置用户ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取用户名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置用户名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取邮箱账号
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置邮箱账号
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取邮箱验证时间
     */
    public function getEmailVerifiedAt(): string
    {
        return $this->emailVerifiedAt;
    }

    /**
     * 设置邮箱验证时间
     */
    public function setEmailVerifiedAt(string $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * 获取记住我Token
     */
    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    /**
     * 设置记住我Token
     */
    public function setRememberToken(string $rememberToken): void
    {
        $this->rememberToken = $rememberToken;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}

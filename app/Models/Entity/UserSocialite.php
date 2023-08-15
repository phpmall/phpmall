<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserSocialiteSchema')]
class UserSocialite
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $userId;

    #[OA\Property(property: 'type', description: '凭证类型:email,wechat', type: 'string')]
    protected string $type;

    #[OA\Property(property: 'identifier', description: '唯一标识:如电子邮箱,openid', type: 'string')]
    protected string $identifier;

    #[OA\Property(property: 'credentials', description: '凭证:密码,token', type: 'string')]
    protected string $credentials;

    #[OA\Property(property: 'remember_token', description: '会话Token', type: 'string')]
    protected string $rememberToken;

    #[OA\Property(property: 'reset_token', description: '重置Token', type: 'string')]
    protected string $resetToken;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deletedAt;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取凭证类型:email,wechat
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置凭证类型:email,wechat
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取唯一标识:如电子邮箱,openid
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * 设置唯一标识:如电子邮箱,openid
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * 获取凭证:密码,token
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }

    /**
     * 设置凭证:密码,token
     */
    public function setCredentials(string $credentials): void
    {
        $this->credentials = $credentials;
    }

    /**
     * 获取会话Token
     */
    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    /**
     * 设置会话Token
     */
    public function setRememberToken(string $rememberToken): void
    {
        $this->rememberToken = $rememberToken;
    }

    /**
     * 获取重置Token
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    /**
     * 设置重置Token
     */
    public function setResetToken(string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}

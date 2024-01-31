<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Generator\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserSocialiteEntity')]
class UserSocialiteEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $userId;

    #[OA\Property(property: 'type', description: '凭证类型', type: 'string')]
    protected string $type;

    #[OA\Property(property: 'identifier', description: '标识', type: 'string')]
    protected string $identifier;

    #[OA\Property(property: 'credentials', description: '凭证或token', type: 'string')]
    protected string $credentials;

    #[OA\Property(property: 'verified_time', description: '验证时间', type: 'string')]
    protected string $verifiedTime;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

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
     * 获取凭证类型
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置凭证类型
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取标识
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * 设置标识
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * 获取凭证或token
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }

    /**
     * 设置凭证或token
     */
    public function setCredentials(string $credentials): void
    {
        $this->credentials = $credentials;
    }

    /**
     * 获取验证时间
     */
    public function getVerifiedTime(): string
    {
        return $this->verifiedTime;
    }

    /**
     * 设置验证时间
     */
    public function setVerifiedTime(string $verifiedTime): void
    {
        $this->verifiedTime = $verifiedTime;
    }

    /**
     * 获取状态:1正常,2禁用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1正常,2禁用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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

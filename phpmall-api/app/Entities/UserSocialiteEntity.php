<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserSocialiteEntity')]
class UserSocialiteEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $user_id;

    #[OA\Property(property: 'type', description: '凭证类型', type: 'string')]
    protected string $type;

    #[OA\Property(property: 'identifier', description: '标识', type: 'string')]
    protected string $identifier;

    #[OA\Property(property: 'credentials', description: '凭证或token', type: 'string')]
    protected string $credentials;

    #[OA\Property(property: 'verified_time', description: '验证时间', type: 'string')]
    protected string $verified_time;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deleted_at;

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
        return $this->user_id;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
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
        return $this->verified_time;
    }

    /**
     * 设置验证时间
     */
    public function setVerifiedTime(string $verified_time): void
    {
        $this->verified_time = $verified_time;
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
        return $this->created_at;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }
}

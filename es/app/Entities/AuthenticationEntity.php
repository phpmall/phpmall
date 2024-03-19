<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AuthenticationEntity')]
class AuthenticationEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $user_id;

    #[OA\Property(property: 'user_uuid', description: '全局ID', type: 'string')]
    protected string $user_uuid;

    #[OA\Property(property: 'type', description: '类型:wechat_open_id,wechat_union_id,ding_talk_open_id', type: 'string')]
    protected string $type;

    #[OA\Property(property: 'identifier', description: '标识:如openid', type: 'string')]
    protected string $identifier;

    #[OA\Property(property: 'credentials', description: '凭证:如密码,token', type: 'string')]
    protected string $credentials;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '删除时间', type: 'string')]
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
     * 获取全局ID
     */
    public function getUserUuid(): string
    {
        return $this->user_uuid;
    }

    /**
     * 设置全局ID
     */
    public function setUserUuid(string $user_uuid): void
    {
        $this->user_uuid = $user_uuid;
    }

    /**
     * 获取类型:wechat_open_id,wechat_union_id,ding_talk_open_id
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型:wechat_open_id,wechat_union_id,ding_talk_open_id
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取标识:如openid
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * 设置标识:如openid
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * 获取凭证:如密码,token
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }

    /**
     * 设置凭证:如密码,token
     */
    public function setCredentials(string $credentials): void
    {
        $this->credentials = $credentials;
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

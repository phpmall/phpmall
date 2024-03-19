<?php

declare(strict_types=1);

namespace App\Http\Responses\Authentication;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AuthenticationResponse')]
class AuthenticationResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'userUuid', description: '全局ID', type: 'string')]
    private string $userUuid;

    #[OA\Property(property: 'type', description: '类型:wechat_open_id,wechat_union_id,ding_talk_open_id', type: 'string')]
    private string $type;

    #[OA\Property(property: 'identifier', description: '标识:如openid', type: 'string')]
    private string $identifier;

    #[OA\Property(property: 'credentials', description: '凭证:如密码,token', type: 'string')]
    private string $credentials;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'createdAt', description: '', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '', type: 'string')]
    private string $deletedAt;

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
     * 获取全局ID
     */
    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    /**
     * 设置全局ID
     */
    public function setUserUuid(string $userUuid): void
    {
        $this->userUuid = $userUuid;
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
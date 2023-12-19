<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommentEntity')]
class CommentEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '父级的ID', type: 'integer')]
    protected int $parentId;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $userId;

    #[OA\Property(property: 'user_name', description: '用户昵称', type: 'string')]
    protected string $userName;

    #[OA\Property(property: 'content_id', description: '内容ID', type: 'integer')]
    protected int $contentId;

    #[OA\Property(property: 'comment', description: '评论内容', type: 'string')]
    protected string $comment;

    #[OA\Property(property: 'rank', description: '评论等级', type: 'integer')]
    protected int $rank;

    #[OA\Property(property: 'user_agent', description: 'User Agent', type: 'string')]
    protected string $userAgent;

    #[OA\Property(property: 'ip_address', description: 'IP地址', type: 'string')]
    protected string $ipAddress;

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
     * 获取父级的ID
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级的ID
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
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
     * 获取用户昵称
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * 设置用户昵称
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * 获取内容ID
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }

    /**
     * 设置内容ID
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    /**
     * 获取评论内容
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * 设置评论内容
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * 获取评论等级
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * 设置评论等级
     */
    public function setRank(int $rank): void
    {
        $this->rank = $rank;
    }

    /**
     * 获取User Agent
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * 设置User Agent
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * 获取IP地址
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * 设置IP地址
     */
    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
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

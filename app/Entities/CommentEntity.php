<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommentEntity')]
class CommentEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '父级的ID', type: 'integer')]
    protected int $parent_id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $user_id;

    #[OA\Property(property: 'user_name', description: '用户昵称', type: 'string')]
    protected string $user_name;

    #[OA\Property(property: 'content_id', description: '内容ID', type: 'integer')]
    protected int $content_id;

    #[OA\Property(property: 'comment', description: '评论内容', type: 'string')]
    protected string $comment;

    #[OA\Property(property: 'rank', description: '评论等级', type: 'integer')]
    protected int $rank;

    #[OA\Property(property: 'user_agent', description: 'User Agent', type: 'string')]
    protected string $user_agent;

    #[OA\Property(property: 'ip_address', description: 'IP地址', type: 'string')]
    protected string $ip_address;

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
     * 获取父级的ID
     */
    public function getParentId(): int
    {
        return $this->parent_id;
    }

    /**
     * 设置父级的ID
     */
    public function setParentId(int $parent_id): void
    {
        $this->parent_id = $parent_id;
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
     * 获取用户昵称
     */
    public function getUserName(): string
    {
        return $this->user_name;
    }

    /**
     * 设置用户昵称
     */
    public function setUserName(string $user_name): void
    {
        $this->user_name = $user_name;
    }

    /**
     * 获取内容ID
     */
    public function getContentId(): int
    {
        return $this->content_id;
    }

    /**
     * 设置内容ID
     */
    public function setContentId(int $content_id): void
    {
        $this->content_id = $content_id;
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
        return $this->user_agent;
    }

    /**
     * 设置User Agent
     */
    public function setUserAgent(string $user_agent): void
    {
        $this->user_agent = $user_agent;
    }

    /**
     * 获取IP地址
     */
    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    /**
     * 设置IP地址
     */
    public function setIpAddress(string $ip_address): void
    {
        $this->ip_address = $ip_address;
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

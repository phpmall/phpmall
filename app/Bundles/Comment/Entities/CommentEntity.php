<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommentEntity')]
class CommentEntity
{
    use DTOHelper;

    const string getCommentId = 'comment_id';

    const string getCommentType = 'comment_type'; // 评论类型

    const string getIdValue = 'id_value'; // 关联ID

    const string getEmail = 'email'; // 邮箱

    const string getUserName = 'user_name'; // 用户名

    const string getContent = 'content'; // 内容

    const string getCommentRank = 'comment_rank'; // 评论等级

    const string getAddTime = 'add_time'; // 添加时间

    const string getIpAddress = 'ip_address'; // IP地址

    const string getStatus = 'status'; // 状态

    const string getParentId = 'parent_id'; // 父级ID

    const string getUserId = 'user_id'; // 用户ID

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'commentId', description: '', type: 'integer')]
    private int $commentId;

    #[OA\Property(property: 'commentType', description: '评论类型', type: 'integer')]
    private int $commentType;

    #[OA\Property(property: 'idValue', description: '关联ID', type: 'integer')]
    private int $idValue;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'userName', description: '用户名', type: 'string')]
    private string $userName;

    #[OA\Property(property: 'content', description: '内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'commentRank', description: '评论等级', type: 'integer')]
    private int $commentRank;

    #[OA\Property(property: 'addTime', description: '添加时间', type: 'integer')]
    private int $addTime;

    #[OA\Property(property: 'ipAddress', description: 'IP地址', type: 'string')]
    private string $ipAddress;

    #[OA\Property(property: 'status', description: '状态', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getCommentId(): int
    {
        return $this->commentId;
    }

    /**
     * 设置
     */
    public function setCommentId(int $commentId): void
    {
        $this->commentId = $commentId;
    }

    /**
     * 获取评论类型
     */
    public function getCommentType(): int
    {
        return $this->commentType;
    }

    /**
     * 设置评论类型
     */
    public function setCommentType(int $commentType): void
    {
        $this->commentType = $commentType;
    }

    /**
     * 获取关联ID
     */
    public function getIdValue(): int
    {
        return $this->idValue;
    }

    /**
     * 设置关联ID
     */
    public function setIdValue(int $idValue): void
    {
        $this->idValue = $idValue;
    }

    /**
     * 获取邮箱
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置邮箱
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取用户名
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * 设置用户名
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * 获取内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取评论等级
     */
    public function getCommentRank(): int
    {
        return $this->commentRank;
    }

    /**
     * 设置评论等级
     */
    public function setCommentRank(int $commentRank): void
    {
        $this->commentRank = $commentRank;
    }

    /**
     * 获取添加时间
     */
    public function getAddTime(): int
    {
        return $this->addTime;
    }

    /**
     * 设置添加时间
     */
    public function setAddTime(int $addTime): void
    {
        $this->addTime = $addTime;
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
     * 获取状态
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取父级ID
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID
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
     * 获取创建时间
     */
    public function getCreatedTime(): string
    {
        return $this->createdTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedTime(string $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedTime(string $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}

<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerLogEntity')]
class SellerLogEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'seller_user_id', description: '商户管理员ID', type: 'integer')]
    protected int $sellerUserId;

    #[OA\Property(property: 'level', description: '日志级别', type: 'integer')]
    protected int $level;

    #[OA\Property(property: 'message', description: '日志内容', type: 'string')]
    protected string $message;

    #[OA\Property(property: 'user_agent', description: 'User Agent', type: 'string')]
    protected string $userAgent;

    #[OA\Property(property: 'ip_address', description: 'IP地址', type: 'string')]
    protected string $ipAddress;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

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
     * 获取商户管理员ID
     */
    public function getSellerUserId(): int
    {
        return $this->sellerUserId;
    }

    /**
     * 设置商户管理员ID
     */
    public function setSellerUserId(int $sellerUserId): void
    {
        $this->sellerUserId = $sellerUserId;
    }

    /**
     * 获取日志级别
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * 设置日志级别
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * 获取日志内容
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * 设置日志内容
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
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
}

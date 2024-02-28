<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserLogEntity')]
class UserLogEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $user_id;

    #[OA\Property(property: 'level', description: '日志级别', type: 'string')]
    protected string $level;

    #[OA\Property(property: 'message', description: '日志内容', type: 'string')]
    protected string $message;

    #[OA\Property(property: 'user_agent', description: 'User Agent', type: 'string')]
    protected string $user_agent;

    #[OA\Property(property: 'ip_address', description: 'IP地址', type: 'string')]
    protected string $ip_address;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

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
     * 获取日志级别
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * 设置日志级别
     */
    public function setLevel(string $level): void
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
}

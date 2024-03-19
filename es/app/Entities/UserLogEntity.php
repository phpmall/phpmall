<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserLogEntity')]
class UserLogEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $user_id;

    #[OA\Property(property: 'event_type', description: '事件类型，用于区分不同的用户操作或系统事件', type: 'string')]
    protected string $event_type;

    #[OA\Property(property: 'event_time', description: '事件发生的时间', type: 'string')]
    protected string $event_time;

    #[OA\Property(property: 'event_details', description: '事件的详细信息，推荐json格式', type: 'string')]
    protected string $event_details;

    #[OA\Property(property: 'ip_address', description: '用户的IP地址', type: 'string')]
    protected string $ip_address;

    #[OA\Property(property: 'user_agent', description: '用户代理字符串', type: 'string')]
    protected string $user_agent;

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
     * 获取事件类型，用于区分不同的用户操作或系统事件
     */
    public function getEventType(): string
    {
        return $this->event_type;
    }

    /**
     * 设置事件类型，用于区分不同的用户操作或系统事件
     */
    public function setEventType(string $event_type): void
    {
        $this->event_type = $event_type;
    }

    /**
     * 获取事件发生的时间
     */
    public function getEventTime(): string
    {
        return $this->event_time;
    }

    /**
     * 设置事件发生的时间
     */
    public function setEventTime(string $event_time): void
    {
        $this->event_time = $event_time;
    }

    /**
     * 获取事件的详细信息，推荐json格式
     */
    public function getEventDetails(): string
    {
        return $this->event_details;
    }

    /**
     * 设置事件的详细信息，推荐json格式
     */
    public function setEventDetails(string $event_details): void
    {
        $this->event_details = $event_details;
    }

    /**
     * 获取用户的IP地址
     */
    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    /**
     * 设置用户的IP地址
     */
    public function setIpAddress(string $ip_address): void
    {
        $this->ip_address = $ip_address;
    }

    /**
     * 获取用户代理字符串
     */
    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    /**
     * 设置用户代理字符串
     */
    public function setUserAgent(string $user_agent): void
    {
        $this->user_agent = $user_agent;
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

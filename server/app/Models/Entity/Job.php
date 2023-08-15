<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'JobSchema')]
class Job
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'queue', description: '', type: 'string')]
    protected string $queue;

    #[OA\Property(property: 'payload', description: '', type: 'string')]
    protected string $payload;

    #[OA\Property(property: 'attempts', description: '', type: 'int')]
    protected int $attempts;

    #[OA\Property(property: 'reserved_at', description: '', type: 'int')]
    protected int $reservedAt;

    #[OA\Property(property: 'available_at', description: '', type: 'int')]
    protected int $availableAt;

    #[OA\Property(property: 'created_at', description: '', type: 'int')]
    protected int $createdAt;

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
     * 获取
     */
    public function getQueue(): string
    {
        return $this->queue;
    }

    /**
     * 设置
     */
    public function setQueue(string $queue): void
    {
        $this->queue = $queue;
    }

    /**
     * 获取
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * 设置
     */
    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * 获取
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * 设置
     */
    public function setAttempts(int $attempts): void
    {
        $this->attempts = $attempts;
    }

    /**
     * 获取
     */
    public function getReservedAt(): int
    {
        return $this->reservedAt;
    }

    /**
     * 设置
     */
    public function setReservedAt(int $reservedAt): void
    {
        $this->reservedAt = $reservedAt;
    }

    /**
     * 获取
     */
    public function getAvailableAt(): int
    {
        return $this->availableAt;
    }

    /**
     * 设置
     */
    public function setAvailableAt(int $availableAt): void
    {
        $this->availableAt = $availableAt;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

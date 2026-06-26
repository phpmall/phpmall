<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Distributor;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerDistributorResponse')]
class DistributorResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '分销商ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'parent_id', description: '上级分销商ID', type: 'integer', nullable: true)]
    private ?int $parentId;

    #[OA\Property(property: 'level', description: '分销等级', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'status', description: '状态:0待审核,1已通过,2已拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'total_commission', description: '累计佣金(分)', type: 'integer')]
    private int $totalCommission;

    #[OA\Property(property: 'settled_commission', description: '已结算佣金(分)', type: 'integer')]
    private int $settledCommission;

    #[OA\Property(property: 'pending_commission', description: '待结算佣金(分)', type: 'integer')]
    private int $pendingCommission;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getTotalCommission(): int
    {
        return $this->totalCommission;
    }

    public function setTotalCommission(int $totalCommission): void
    {
        $this->totalCommission = $totalCommission;
    }

    public function getSettledCommission(): int
    {
        return $this->settledCommission;
    }

    public function setSettledCommission(int $settledCommission): void
    {
        $this->settledCommission = $settledCommission;
    }

    public function getPendingCommission(): int
    {
        return $this->pendingCommission;
    }

    public function setPendingCommission(int $pendingCommission): void
    {
        $this->pendingCommission = $pendingCommission;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

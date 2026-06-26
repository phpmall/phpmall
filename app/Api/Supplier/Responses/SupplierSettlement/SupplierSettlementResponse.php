<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\SupplierSettlement;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierSupplierSettlementResponse')]
class SupplierSettlementResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '结算ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'settlement_no', description: '结算单号', type: 'string')]
    private string $settlementNo;

    #[OA\Property(property: 'supplier_id', description: '供应商ID', type: 'integer')]
    private int $supplierId;

    #[OA\Property(property: 'period_start', description: '结算周期开始', type: 'string', format: 'date-time')]
    private string $periodStart;

    #[OA\Property(property: 'period_end', description: '结算周期结束', type: 'string', format: 'date-time')]
    private string $periodEnd;

    #[OA\Property(property: 'total_amount', description: '结算总金额(分)', type: 'integer')]
    private int $totalAmount;

    #[OA\Property(property: 'settled_amount', description: '已结算金额(分)', type: 'integer')]
    private int $settledAmount;

    #[OA\Property(property: 'status', description: '结算状态:0待结算,1结算中,2已结算', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'settled_at', description: '结算时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $settledAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSettlementNo(): string
    {
        return $this->settlementNo;
    }

    public function setSettlementNo(string $settlementNo): void
    {
        $this->settlementNo = $settlementNo;
    }

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    public function setSupplierId(int $supplierId): void
    {
        $this->supplierId = $supplierId;
    }

    public function getPeriodStart(): string
    {
        return $this->periodStart;
    }

    public function setPeriodStart(string $periodStart): void
    {
        $this->periodStart = $periodStart;
    }

    public function getPeriodEnd(): string
    {
        return $this->periodEnd;
    }

    public function setPeriodEnd(string $periodEnd): void
    {
        $this->periodEnd = $periodEnd;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getSettledAmount(): int
    {
        return $this->settledAmount;
    }

    public function setSettledAmount(int $settledAmount): void
    {
        $this->settledAmount = $settledAmount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getSettledAt(): ?string
    {
        return $this->settledAt;
    }

    public function setSettledAt(?string $settledAt): void
    {
        $this->settledAt = $settledAt;
    }
}

<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Settlement;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerSettlementResponse')]
class SettlementResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '结算ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'settlement_no', description: '结算单号', type: 'string')]
    private string $settlementNo;

    #[OA\Property(property: 'period_start', description: '结算周期开始', type: 'string', format: 'date-time')]
    private string $periodStart;

    #[OA\Property(property: 'period_end', description: '结算周期结束', type: 'string', format: 'date-time')]
    private string $periodEnd;

    #[OA\Property(property: 'total_amount', description: '订单总金额(分)', type: 'integer')]
    private int $totalAmount;

    #[OA\Property(property: 'commission_amount', description: '佣金金额(分)', type: 'integer')]
    private int $commissionAmount;

    #[OA\Property(property: 'refund_amount', description: '退款金额(分)', type: 'integer')]
    private int $refundAmount;

    #[OA\Property(property: 'settlement_amount', description: '实际结算金额(分)', type: 'integer')]
    private int $settlementAmount;

    #[OA\Property(property: 'status', description: '结算状态:0待结算,1结算中,2已结算', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'settled_at', description: '结算时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $settledAt;

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

    public function getSettlementNo(): string
    {
        return $this->settlementNo;
    }

    public function setSettlementNo(string $settlementNo): void
    {
        $this->settlementNo = $settlementNo;
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

    public function getCommissionAmount(): int
    {
        return $this->commissionAmount;
    }

    public function setCommissionAmount(int $commissionAmount): void
    {
        $this->commissionAmount = $commissionAmount;
    }

    public function getRefundAmount(): int
    {
        return $this->refundAmount;
    }

    public function setRefundAmount(int $refundAmount): void
    {
        $this->refundAmount = $refundAmount;
    }

    public function getSettlementAmount(): int
    {
        return $this->settlementAmount;
    }

    public function setSettlementAmount(int $settlementAmount): void
    {
        $this->settlementAmount = $settlementAmount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getSettledAt(): ?string
    {
        return $this->settledAt;
    }

    public function setSettledAt(?string $settledAt): void
    {
        $this->settledAt = $settledAt;
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

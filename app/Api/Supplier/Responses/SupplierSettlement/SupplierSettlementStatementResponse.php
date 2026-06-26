<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\SupplierSettlement;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierSupplierSettlementStatementResponse')]
class SupplierSettlementStatementResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'settlement_id', description: '结算ID', type: 'integer')]
    private int $settlementId;

    #[OA\Property(property: 'settlement_no', description: '结算单号', type: 'string')]
    private string $settlementNo;

    #[OA\Property(property: 'period_start', description: '结算周期开始', type: 'string', format: 'date-time')]
    private string $periodStart;

    #[OA\Property(property: 'period_end', description: '结算周期结束', type: 'string', format: 'date-time')]
    private string $periodEnd;

    #[OA\Property(property: 'total_amount', description: '结算总金额(分)', type: 'integer')]
    private int $totalAmount;

    #[OA\Property(property: 'total_orders', description: '订单总数', type: 'integer')]
    private int $totalOrders;

    #[OA\Property(
        property: 'order_details',
        description: '订单明细列表',
        type: 'array',
        items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'order_id', type: 'integer'),
            new OA\Property(property: 'order_no', type: 'string'),
            new OA\Property(property: 'order_amount', type: 'integer', description: '订单金额(分)'),
            new OA\Property(property: 'settlement_amount', type: 'integer', description: '结算金额(分)'),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        ]),
        nullable: true
    )]
    private ?array $orderDetails;

    public function getSettlementId(): int
    {
        return $this->settlementId;
    }

    public function setSettlementId(int $settlementId): void
    {
        $this->settlementId = $settlementId;
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

    public function getTotalOrders(): int
    {
        return $this->totalOrders;
    }

    public function setTotalOrders(int $totalOrders): void
    {
        $this->totalOrders = $totalOrders;
    }

    public function getOrderDetails(): ?array
    {
        return $this->orderDetails;
    }

    public function setOrderDetails(?array $orderDetails): void
    {
        $this->orderDetails = $orderDetails;
    }
}

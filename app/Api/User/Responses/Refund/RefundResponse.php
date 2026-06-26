<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Refund;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RefundResponse')]
class RefundResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '退款ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'refund_no', description: '退款单号', type: 'string')]
    private string $refundNo;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'order_no', description: '订单编号', type: 'string')]
    private string $orderNo;

    #[OA\Property(property: 'type', description: '退款类型:refund,return_refund', type: 'string')]
    private string $type;

    #[OA\Property(property: 'amount', description: '退款金额(分)', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'reason', description: '退款原因', type: 'string')]
    private string $reason;

    #[OA\Property(property: 'description', description: '补充说明', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'images', description: '凭证图片', type: 'array', items: new OA\Items(type: 'string', format: 'uri'))]
    private array $images;

    #[OA\Property(property: 'status', description: '状态:0待审核,1审核通过,2审核拒绝,3退款中,4退款成功,5退款失败,6已取消', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'reject_reason', description: '拒绝原因', type: 'string', nullable: true)]
    private ?string $rejectReason;

    #[OA\Property(property: 'created_at', description: '申请时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'processed_at', description: '处理时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $processedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRefundNo(): string
    {
        return $this->refundNo;
    }

    public function setRefundNo(string $refundNo): void
    {
        $this->refundNo = $refundNo;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getRejectReason(): ?string
    {
        return $this->rejectReason;
    }

    public function setRejectReason(?string $rejectReason): void
    {
        $this->rejectReason = $rejectReason;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getProcessedAt(): ?string
    {
        return $this->processedAt;
    }

    public function setProcessedAt(?string $processedAt): void
    {
        $this->processedAt = $processedAt;
    }
}

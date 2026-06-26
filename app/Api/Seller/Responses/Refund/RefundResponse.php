<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Refund;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerRefundResponse')]
class RefundResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '退款ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'refund_no', description: '退款编号', type: 'string')]
    private string $refundNo;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'status', description: '退款状态:0待审核,1已通过,2已拒绝,3仲裁中,4已关闭', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'refund_amount', description: '退款金额(分)', type: 'integer')]
    private int $refundAmount;

    #[OA\Property(property: 'reason', description: '退款原因', type: 'string')]
    private string $reason;

    #[OA\Property(property: 'description', description: '退款说明', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'evidence_images', description: '凭证图片', type: 'array', items: new OA\Items(type: 'string'))]
    private array $evidenceImages;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getRefundAmount(): int
    {
        return $this->refundAmount;
    }

    public function setRefundAmount(int $refundAmount): void
    {
        $this->refundAmount = $refundAmount;
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

    public function getEvidenceImages(): array
    {
        return $this->evidenceImages;
    }

    public function setEvidenceImages(array $evidenceImages): void
    {
        $this->evidenceImages = $evidenceImages;
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

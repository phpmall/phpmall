<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Complaint;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerComplaintResponse')]
class ComplaintResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '投诉ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'complaint_no', description: '投诉编号', type: 'string')]
    private string $complaintNo;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'type', description: '投诉类型:1商品质量,2服务态度,3物流问题,4其他', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'status', description: '状态:0待处理,1已回应,2已处理,3已申诉', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'content', description: '投诉内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'evidence', description: '证据图片列表', type: 'array', items: new OA\Items(type: 'string'))]
    private array $evidence;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'responded_at', description: '回应时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $respondedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getComplaintNo(): string
    {
        return $this->complaintNo;
    }

    public function setComplaintNo(string $complaintNo): void
    {
        $this->complaintNo = $complaintNo;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getEvidence(): array
    {
        return $this->evidence;
    }

    public function setEvidence(array $evidence): void
    {
        $this->evidence = $evidence;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getRespondedAt(): ?string
    {
        return $this->respondedAt;
    }

    public function setRespondedAt(?string $respondedAt): void
    {
        $this->respondedAt = $respondedAt;
    }
}

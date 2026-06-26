<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Complaint;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ComplaintResponse')]
class ComplaintResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '投诉ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'complaint_no', description: '投诉单号', type: 'string')]
    private string $complaintNo;

    #[OA\Property(property: 'type', description: '投诉类型:product,service,logistics,other', type: 'string')]
    private string $type;

    #[OA\Property(property: 'target_type', description: '投诉对象类型:order,product,shop', type: 'string')]
    private string $targetType;

    #[OA\Property(property: 'target_id', description: '投诉对象ID', type: 'integer')]
    private int $targetId;

    #[OA\Property(property: 'target_name', description: '投诉对象名称', type: 'string')]
    private string $targetName;

    #[OA\Property(property: 'reason', description: '投诉原因', type: 'string')]
    private string $reason;

    #[OA\Property(property: 'description', description: '详细描述', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'images', description: '凭证图片', type: 'array', items: new OA\Items(type: 'string', format: 'uri'))]
    private array $images;

    #[OA\Property(property: 'contact', description: '联系方式', type: 'string', nullable: true)]
    private ?string $contact;

    #[OA\Property(property: 'status', description: '状态:0待处理,1处理中,2已处理,3已关闭', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'result', description: '处理结果', type: 'string', nullable: true)]
    private ?string $result;

    #[OA\Property(property: 'created_at', description: '投诉时间', type: 'string', format: 'date-time')]
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

    public function getComplaintNo(): string
    {
        return $this->complaintNo;
    }

    public function setComplaintNo(string $complaintNo): void
    {
        $this->complaintNo = $complaintNo;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getTargetType(): string
    {
        return $this->targetType;
    }

    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    public function getTargetId(): int
    {
        return $this->targetId;
    }

    public function setTargetId(int $targetId): void
    {
        $this->targetId = $targetId;
    }

    public function getTargetName(): string
    {
        return $this->targetName;
    }

    public function setTargetName(string $targetName): void
    {
        $this->targetName = $targetName;
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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): void
    {
        $this->contact = $contact;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): void
    {
        $this->result = $result;
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

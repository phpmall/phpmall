<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\MerchantApplication;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerMerchantApplicationStatusResponse')]
class MerchantApplicationStatusResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '申请ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '商家名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'status', description: '申请状态:0待审核,1已通过,2已拒绝,3已撤销', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'reject_reason', description: '拒绝原因', type: 'string', nullable: true)]
    private ?string $rejectReason;

    #[OA\Property(property: 'submitted_at', description: '提交时间', type: 'string', format: 'date-time')]
    private string $submittedAt;

    #[OA\Property(property: 'reviewed_at', description: '审核时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $reviewedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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

    public function getSubmittedAt(): string
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(string $submittedAt): void
    {
        $this->submittedAt = $submittedAt;
    }

    public function getReviewedAt(): ?string
    {
        return $this->reviewedAt;
    }

    public function setReviewedAt(?string $reviewedAt): void
    {
        $this->reviewedAt = $reviewedAt;
    }
}

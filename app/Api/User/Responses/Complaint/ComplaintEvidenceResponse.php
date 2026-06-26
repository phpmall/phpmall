<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Complaint;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ComplaintEvidenceResponse')]
class ComplaintEvidenceResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'complaint_id', description: '投诉ID', type: 'integer')]
    private int $complaintId;

    #[OA\Property(property: 'evidence', description: '补充证据图片', type: 'array', items: new OA\Items(type: 'string', format: 'uri'))]
    private array $evidence;

    #[OA\Property(property: 'description', description: '补充说明', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'submitted_at', description: '提交时间', type: 'string', format: 'date-time')]
    private string $submittedAt;

    public function getComplaintId(): int
    {
        return $this->complaintId;
    }

    public function setComplaintId(int $complaintId): void
    {
        $this->complaintId = $complaintId;
    }

    public function getEvidence(): array
    {
        return $this->evidence;
    }

    public function setEvidence(array $evidence): void
    {
        $this->evidence = $evidence;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getSubmittedAt(): string
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(string $submittedAt): void
    {
        $this->submittedAt = $submittedAt;
    }
}

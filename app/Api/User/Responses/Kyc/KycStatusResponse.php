<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Kyc;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'KycStatusResponse')]
class KycStatusResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'status', description: '实名状态:0未认证,1审核中,2已通过,3已拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'real_name', description: '真实姓名', type: 'string', nullable: true)]
    private ?string $realName;

    #[OA\Property(property: 'id_number', description: '身份证号(脱敏)', type: 'string', nullable: true)]
    private ?string $idNumber;

    #[OA\Property(property: 'submitted_at', description: '提交时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $submittedAt;

    #[OA\Property(property: 'verified_at', description: '认证通过时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $verifiedAt;

    #[OA\Property(property: 'reject_reason', description: '拒绝原因', type: 'string', nullable: true)]
    private ?string $rejectReason;

    #[OA\Property(property: 'can_resubmit', description: '是否可以重新提交:0否，1是', type: 'integer')]
    private int $canResubmit;

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getRealName(): ?string
    {
        return $this->realName;
    }

    public function setRealName(?string $realName): void
    {
        $this->realName = $realName;
    }

    public function getIdNumber(): ?string
    {
        return $this->idNumber;
    }

    public function setIdNumber(?string $idNumber): void
    {
        $this->idNumber = $idNumber;
    }

    public function getSubmittedAt(): ?string
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(?string $submittedAt): void
    {
        $this->submittedAt = $submittedAt;
    }

    public function getVerifiedAt(): ?string
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?string $verifiedAt): void
    {
        $this->verifiedAt = $verifiedAt;
    }

    public function getRejectReason(): ?string
    {
        return $this->rejectReason;
    }

    public function setRejectReason(?string $rejectReason): void
    {
        $this->rejectReason = $rejectReason;
    }

    public function getCanResubmit(): int
    {
        return $this->canResubmit;
    }

    public function setCanResubmit(int $canResubmit): void
    {
        $this->canResubmit = $canResubmit;
    }
}

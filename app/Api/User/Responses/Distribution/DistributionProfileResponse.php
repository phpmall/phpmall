<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Distribution;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'DistributionProfileResponse')]
class DistributionProfileResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '分销商ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'status', description: '分销状态:0未开通,1审核中,2已通过,3已拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'level', description: '分销等级', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'level_name', description: '等级名称', type: 'string')]
    private string $levelName;

    #[OA\Property(property: 'total_commission', description: '累计佣金(分)', type: 'integer')]
    private int $totalCommission;

    #[OA\Property(property: 'available_commission', description: '可提现佣金(分)', type: 'integer')]
    private int $availableCommission;

    #[OA\Property(property: 'frozen_commission', description: '冻结佣金(分)', type: 'integer')]
    private int $frozenCommission;

    #[OA\Property(property: 'total_withdraw', description: '累计提现(分)', type: 'integer')]
    private int $totalWithdraw;

    #[OA\Property(property: 'invite_code', description: '邀请码', type: 'string', nullable: true)]
    private ?string $inviteCode;

    #[OA\Property(property: 'invite_url', description: '邀请链接', type: 'string', nullable: true)]
    private ?string $inviteUrl;

    #[OA\Property(property: 'created_at', description: '加入时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getLevelName(): string
    {
        return $this->levelName;
    }

    public function setLevelName(string $levelName): void
    {
        $this->levelName = $levelName;
    }

    public function getTotalCommission(): int
    {
        return $this->totalCommission;
    }

    public function setTotalCommission(int $totalCommission): void
    {
        $this->totalCommission = $totalCommission;
    }

    public function getAvailableCommission(): int
    {
        return $this->availableCommission;
    }

    public function setAvailableCommission(int $availableCommission): void
    {
        $this->availableCommission = $availableCommission;
    }

    public function getFrozenCommission(): int
    {
        return $this->frozenCommission;
    }

    public function setFrozenCommission(int $frozenCommission): void
    {
        $this->frozenCommission = $frozenCommission;
    }

    public function getTotalWithdraw(): int
    {
        return $this->totalWithdraw;
    }

    public function setTotalWithdraw(int $totalWithdraw): void
    {
        $this->totalWithdraw = $totalWithdraw;
    }

    public function getInviteCode(): ?string
    {
        return $this->inviteCode;
    }

    public function setInviteCode(?string $inviteCode): void
    {
        $this->inviteCode = $inviteCode;
    }

    public function getInviteUrl(): ?string
    {
        return $this->inviteUrl;
    }

    public function setInviteUrl(?string $inviteUrl): void
    {
        $this->inviteUrl = $inviteUrl;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

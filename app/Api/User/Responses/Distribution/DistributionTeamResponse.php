<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Distribution;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'DistributionTeamResponse')]
class DistributionTeamResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '成员ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'nickname', description: '昵称', type: 'string')]
    private string $nickname;

    #[OA\Property(property: 'avatar', description: '头像', type: 'string', nullable: true)]
    private ?string $avatar;

    #[OA\Property(property: 'level', description: '层级:1直推,2间推', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'join_at', description: '加入时间', type: 'string', format: 'date-time')]
    private string $joinAt;

    #[OA\Property(property: 'total_orders', description: '累计订单', type: 'integer')]
    private int $totalOrders;

    #[OA\Property(property: 'total_commission', description: '贡献佣金(分)', type: 'integer')]
    private int $totalCommission;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getJoinAt(): string
    {
        return $this->joinAt;
    }

    public function setJoinAt(string $joinAt): void
    {
        $this->joinAt = $joinAt;
    }

    public function getTotalOrders(): int
    {
        return $this->totalOrders;
    }

    public function setTotalOrders(int $totalOrders): void
    {
        $this->totalOrders = $totalOrders;
    }

    public function getTotalCommission(): int
    {
        return $this->totalCommission;
    }

    public function setTotalCommission(int $totalCommission): void
    {
        $this->totalCommission = $totalCommission;
    }
}

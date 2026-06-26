<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Message;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MessageUnreadCountResponse')]
class MessageUnreadCountResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'total', description: '总未读消息数', type: 'integer')]
    private int $total;

    #[OA\Property(property: 'system', description: '系统消息未读数', type: 'integer')]
    private int $system;

    #[OA\Property(property: 'order', description: '订单消息未读数', type: 'integer')]
    private int $order;

    #[OA\Property(property: 'activity', description: '活动消息未读数', type: 'integer')]
    private int $activity;

    #[OA\Property(property: 'promotion', description: '促销消息未读数', type: 'integer')]
    private int $promotion;

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getSystem(): int
    {
        return $this->system;
    }

    public function setSystem(int $system): void
    {
        $this->system = $system;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): void
    {
        $this->order = $order;
    }

    public function getActivity(): int
    {
        return $this->activity;
    }

    public function setActivity(int $activity): void
    {
        $this->activity = $activity;
    }

    public function getPromotion(): int
    {
        return $this->promotion;
    }

    public function setPromotion(int $promotion): void
    {
        $this->promotion = $promotion;
    }
}

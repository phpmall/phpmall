<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Settings;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerSettingsResponse')]
class SettingsResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'notification_settings', description: '通知设置', type: 'object')]
    private array $notificationSettings;

    #[OA\Property(property: 'logistics_settings', description: '物流设置', type: 'object')]
    private array $logisticsSettings;

    #[OA\Property(property: 'refund_settings', description: '退款设置', type: 'object')]
    private array $refundSettings;

    #[OA\Property(property: 'other_settings', description: '其他设置', type: 'object')]
    private array $otherSettings;

    public function getNotificationSettings(): array
    {
        return $this->notificationSettings;
    }

    public function setNotificationSettings(array $notificationSettings): void
    {
        $this->notificationSettings = $notificationSettings;
    }

    public function getLogisticsSettings(): array
    {
        return $this->logisticsSettings;
    }

    public function setLogisticsSettings(array $logisticsSettings): void
    {
        $this->logisticsSettings = $logisticsSettings;
    }

    public function getRefundSettings(): array
    {
        return $this->refundSettings;
    }

    public function setRefundSettings(array $refundSettings): void
    {
        $this->refundSettings = $refundSettings;
    }

    public function getOtherSettings(): array
    {
        return $this->otherSettings;
    }

    public function setOtherSettings(array $otherSettings): void
    {
        $this->otherSettings = $otherSettings;
    }
}

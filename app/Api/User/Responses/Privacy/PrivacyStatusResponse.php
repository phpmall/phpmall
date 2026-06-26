<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Privacy;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PrivacyStatusResponse')]
class PrivacyStatusResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'data_collection_enabled', description: '数据收集是否启用:0否，1是', type: 'integer')]
    private int $dataCollectionEnabled;

    #[OA\Property(property: 'marketing_enabled', description: '营销推送是否启用:0否，1是', type: 'integer')]
    private int $marketingEnabled;

    #[OA\Property(property: 'analytics_enabled', description: '分析统计是否启用:0否，1是', type: 'integer')]
    private int $analyticsEnabled;

    #[OA\Property(property: 'third_party_enabled', description: '第三方共享是否启用:0否，1是', type: 'integer')]
    private int $thirdPartyEnabled;

    #[OA\Property(property: 'last_updated_at', description: '最后更新时间', type: 'string', format: 'date-time')]
    private string $lastUpdatedAt;

    #[OA\Property(property: 'consent_history', description: '同意记录', type: 'array', items: new OA\Items(type: 'object', properties: [
        new OA\Property(property: 'type', type: 'string', description: '同意类型'),
        new OA\Property(property: 'consented', type: 'integer', description: '是否同意:0否，1是'),
        new OA\Property(property: 'consented_at', type: 'string', format: 'date-time', description: '同意时间'),
    ]))]
    private array $consentHistory;

    public function getDataCollectionEnabled(): int
    {
        return $this->dataCollectionEnabled;
    }

    public function setDataCollectionEnabled(int $dataCollectionEnabled): void
    {
        $this->dataCollectionEnabled = $dataCollectionEnabled;
    }

    public function getMarketingEnabled(): int
    {
        return $this->marketingEnabled;
    }

    public function setMarketingEnabled(int $marketingEnabled): void
    {
        $this->marketingEnabled = $marketingEnabled;
    }

    public function getAnalyticsEnabled(): int
    {
        return $this->analyticsEnabled;
    }

    public function setAnalyticsEnabled(int $analyticsEnabled): void
    {
        $this->analyticsEnabled = $analyticsEnabled;
    }

    public function getThirdPartyEnabled(): int
    {
        return $this->thirdPartyEnabled;
    }

    public function setThirdPartyEnabled(int $thirdPartyEnabled): void
    {
        $this->thirdPartyEnabled = $thirdPartyEnabled;
    }

    public function getLastUpdatedAt(): string
    {
        return $this->lastUpdatedAt;
    }

    public function setLastUpdatedAt(string $lastUpdatedAt): void
    {
        $this->lastUpdatedAt = $lastUpdatedAt;
    }

    public function getConsentHistory(): array
    {
        return $this->consentHistory;
    }

    public function setConsentHistory(array $consentHistory): void
    {
        $this->consentHistory = $consentHistory;
    }
}

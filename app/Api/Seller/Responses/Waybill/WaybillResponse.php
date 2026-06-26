<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Waybill;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerWaybillResponse')]
class WaybillResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '运单ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'waybill_no', description: '运单号', type: 'string')]
    private string $waybillNo;

    #[OA\Property(property: 'logistics_company', description: '物流公司', type: 'string')]
    private string $logisticsCompany;

    #[OA\Property(property: 'tracking_no', description: '物流跟踪号', type: 'string')]
    private string $trackingNo;

    #[OA\Property(property: 'status', description: '运单状态:0待打印,1已打印,2已取消', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'print_url', description: '打印地址', type: 'string', nullable: true)]
    private ?string $printUrl;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getWaybillNo(): string
    {
        return $this->waybillNo;
    }

    public function setWaybillNo(string $waybillNo): void
    {
        $this->waybillNo = $waybillNo;
    }

    public function getLogisticsCompany(): string
    {
        return $this->logisticsCompany;
    }

    public function setLogisticsCompany(string $logisticsCompany): void
    {
        $this->logisticsCompany = $logisticsCompany;
    }

    public function getTrackingNo(): string
    {
        return $this->trackingNo;
    }

    public function setTrackingNo(string $trackingNo): void
    {
        $this->trackingNo = $trackingNo;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getPrintUrl(): ?string
    {
        return $this->printUrl;
    }

    public function setPrintUrl(?string $printUrl): void
    {
        $this->printUrl = $printUrl;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

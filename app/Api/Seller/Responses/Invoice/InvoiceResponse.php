<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Invoice;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerInvoiceResponse')]
class InvoiceResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '发票ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'invoice_no', description: '发票号码', type: 'string')]
    private string $invoiceNo;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'type', description: '发票类型:1增值税普通发票,2增值税专用发票', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'title', description: '发票抬头', type: 'string')]
    private string $title;

    #[OA\Property(property: 'tax_no', description: '税号', type: 'string', nullable: true)]
    private ?string $taxNo;

    #[OA\Property(property: 'amount', description: '发票金额(分)', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'status', description: '状态:0待开具,1已开具,2已红冲', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'issued_at', description: '开具时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $issuedAt;

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

    public function getInvoiceNo(): string
    {
        return $this->invoiceNo;
    }

    public function setInvoiceNo(string $invoiceNo): void
    {
        $this->invoiceNo = $invoiceNo;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTaxNo(): ?string
    {
        return $this->taxNo;
    }

    public function setTaxNo(?string $taxNo): void
    {
        $this->taxNo = $taxNo;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getIssuedAt(): ?string
    {
        return $this->issuedAt;
    }

    public function setIssuedAt(?string $issuedAt): void
    {
        $this->issuedAt = $issuedAt;
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

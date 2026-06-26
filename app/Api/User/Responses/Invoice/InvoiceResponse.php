<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Invoice;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'InvoiceResponse')]
class InvoiceResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '发票ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'invoice_no', description: '发票编号', type: 'string')]
    private string $invoiceNo;

    #[OA\Property(property: 'type', description: '发票类型:personal,company', type: 'string')]
    private string $type;

    #[OA\Property(property: 'title', description: '发票抬头', type: 'string')]
    private string $title;

    #[OA\Property(property: 'tax_number', description: '纳税人识别号', type: 'string')]
    private string $taxNumber;

    #[OA\Property(property: 'email', description: '接收邮箱', type: 'string', nullable: true)]
    private ?string $email;

    #[OA\Property(property: 'phone', description: '联系电话', type: 'string', nullable: true)]
    private ?string $phone;

    #[OA\Property(property: 'address', description: '注册地址', type: 'string', nullable: true)]
    private ?string $address;

    #[OA\Property(property: 'bank_name', description: '开户银行', type: 'string', nullable: true)]
    private ?string $bankName;

    #[OA\Property(property: 'bank_account', description: '银行账号', type: 'string', nullable: true)]
    private ?string $bankAccount;

    #[OA\Property(property: 'amount', description: '发票金额(分)', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'status', description: '状态:0待开具,1已开具,2已发送,3已作废', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'download_url', description: '下载链接', type: 'string', nullable: true)]
    private ?string $downloadUrl;

    #[OA\Property(property: 'created_at', description: '申请时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'issued_at', description: '开具时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $issuedAt;

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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
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

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    public function setBankName(?string $bankName): void
    {
        $this->bankName = $bankName;
    }

    public function getBankAccount(): ?string
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?string $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
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

    public function getDownloadUrl(): ?string
    {
        return $this->downloadUrl;
    }

    public function setDownloadUrl(?string $downloadUrl): void
    {
        $this->downloadUrl = $downloadUrl;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getIssuedAt(): ?string
    {
        return $this->issuedAt;
    }

    public function setIssuedAt(?string $issuedAt): void
    {
        $this->issuedAt = $issuedAt;
    }
}

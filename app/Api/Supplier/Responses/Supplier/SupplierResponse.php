<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\Supplier;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierSupplierResponse')]
class SupplierResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '供应商ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'company_name', description: '公司名称', type: 'string')]
    private string $companyName;

    #[OA\Property(property: 'contact_name', description: '联系人姓名', type: 'string')]
    private string $contactName;

    #[OA\Property(property: 'contact_phone', description: '联系人电话', type: 'string')]
    private string $contactPhone;

    #[OA\Property(property: 'contact_email', description: '联系人邮箱', type: 'string', nullable: true)]
    private ?string $contactEmail;

    #[OA\Property(property: 'address', description: '公司地址', type: 'string', nullable: true)]
    private ?string $address;

    #[OA\Property(property: 'business_license', description: '营业执照号', type: 'string', nullable: true)]
    private ?string $businessLicense;

    #[OA\Property(property: 'bank_name', description: '开户银行', type: 'string', nullable: true)]
    private ?string $bankName;

    #[OA\Property(property: 'bank_account', description: '银行账号', type: 'string', nullable: true)]
    private ?string $bankAccount;

    #[OA\Property(property: 'tax_no', description: '纳税人识别号', type: 'string', nullable: true)]
    private ?string $taxNo;

    #[OA\Property(property: 'status', description: '状态:0待审核,1正常,2禁用', type: 'integer')]
    private int $status;

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

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function setContactName(string $contactName): void
    {
        $this->contactName = $contactName;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(string $contactPhone): void
    {
        $this->contactPhone = $contactPhone;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getBusinessLicense(): ?string
    {
        return $this->businessLicense;
    }

    public function setBusinessLicense(?string $businessLicense): void
    {
        $this->businessLicense = $businessLicense;
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

    public function getTaxNo(): ?string
    {
        return $this->taxNo;
    }

    public function setTaxNo(?string $taxNo): void
    {
        $this->taxNo = $taxNo;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
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

<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\MerchantSettlementAccount;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerMerchantSettlementAccountListResponse')]
class MerchantSettlementAccountListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '账户ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'account_type', description: '账户类型:1对公,2对私', type: 'integer')]
    private int $accountType;

    #[OA\Property(property: 'account_name', description: '账户名称', type: 'string')]
    private string $accountName;

    #[OA\Property(property: 'account_number', description: '账号(脱敏)', type: 'string')]
    private string $accountNumber;

    #[OA\Property(property: 'bank_name', description: '开户银行', type: 'string')]
    private string $bankName;

    #[OA\Property(property: 'bank_branch', description: '开户支行', type: 'string', nullable: true)]
    private ?string $bankBranch;

    #[OA\Property(property: 'bank_code', description: '银行联行号', type: 'string', nullable: true)]
    private ?string $bankCode;

    #[OA\Property(property: 'phone', description: '预留手机号', type: 'string', nullable: true)]
    private ?string $phone;

    #[OA\Property(property: 'is_default', description: '是否默认:0否,1是', type: 'integer')]
    private int $isDefault;

    #[OA\Property(property: 'status', description: '状态:0禁用,1启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string', format: 'date-time')]
    private string $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getAccountType(): int
    {
        return $this->accountType;
    }

    public function setAccountType(int $accountType): void
    {
        $this->accountType = $accountType;
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    public function setAccountName(string $accountName): void
    {
        $this->accountName = $accountName;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    public function getBankName(): string
    {
        return $this->bankName;
    }

    public function setBankName(string $bankName): void
    {
        $this->bankName = $bankName;
    }

    public function getBankBranch(): ?string
    {
        return $this->bankBranch;
    }

    public function setBankBranch(?string $bankBranch): void
    {
        $this->bankBranch = $bankBranch;
    }

    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    public function setBankCode(?string $bankCode): void
    {
        $this->bankCode = $bankCode;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getIsDefault(): int
    {
        return $this->isDefault;
    }

    public function setIsDefault(int $isDefault): void
    {
        $this->isDefault = $isDefault;
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

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}

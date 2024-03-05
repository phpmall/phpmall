<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MerchantEntity')]
class MerchantEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'seller_user_id', description: '卖家创始人ID', type: 'integer')]
    protected int $seller_user_id;

    #[OA\Property(property: 'company_name', description: '企业名称', type: 'string')]
    protected string $company_name;

    #[OA\Property(property: 'company_address', description: '企业地址', type: 'string')]
    protected string $company_address;

    #[OA\Property(property: 'legal_person', description: '企业法人姓名', type: 'string')]
    protected string $legal_person;

    #[OA\Property(property: 'business_license', description: '企业营业执照号', type: 'string')]
    protected string $business_license;

    #[OA\Property(property: 'tax_registration', description: '企业税务登记号', type: 'string')]
    protected string $tax_registration;

    #[OA\Property(property: 'opening_bank', description: '开户银行', type: 'string')]
    protected string $opening_bank;

    #[OA\Property(property: 'bank_account', description: '企业银行账户', type: 'string')]
    protected string $bank_account;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deleted_at;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取卖家创始人ID
     */
    public function getSellerUserId(): int
    {
        return $this->seller_user_id;
    }

    /**
     * 设置卖家创始人ID
     */
    public function setSellerUserId(int $seller_user_id): void
    {
        $this->seller_user_id = $seller_user_id;
    }

    /**
     * 获取企业名称
     */
    public function getCompanyName(): string
    {
        return $this->company_name;
    }

    /**
     * 设置企业名称
     */
    public function setCompanyName(string $company_name): void
    {
        $this->company_name = $company_name;
    }

    /**
     * 获取企业地址
     */
    public function getCompanyAddress(): string
    {
        return $this->company_address;
    }

    /**
     * 设置企业地址
     */
    public function setCompanyAddress(string $company_address): void
    {
        $this->company_address = $company_address;
    }

    /**
     * 获取企业法人姓名
     */
    public function getLegalPerson(): string
    {
        return $this->legal_person;
    }

    /**
     * 设置企业法人姓名
     */
    public function setLegalPerson(string $legal_person): void
    {
        $this->legal_person = $legal_person;
    }

    /**
     * 获取企业营业执照号
     */
    public function getBusinessLicense(): string
    {
        return $this->business_license;
    }

    /**
     * 设置企业营业执照号
     */
    public function setBusinessLicense(string $business_license): void
    {
        $this->business_license = $business_license;
    }

    /**
     * 获取企业税务登记号
     */
    public function getTaxRegistration(): string
    {
        return $this->tax_registration;
    }

    /**
     * 设置企业税务登记号
     */
    public function setTaxRegistration(string $tax_registration): void
    {
        $this->tax_registration = $tax_registration;
    }

    /**
     * 获取开户银行
     */
    public function getOpeningBank(): string
    {
        return $this->opening_bank;
    }

    /**
     * 设置开户银行
     */
    public function setOpeningBank(string $opening_bank): void
    {
        $this->opening_bank = $opening_bank;
    }

    /**
     * 获取企业银行账户
     */
    public function getBankAccount(): string
    {
        return $this->bank_account;
    }

    /**
     * 设置企业银行账户
     */
    public function setBankAccount(string $bank_account): void
    {
        $this->bank_account = $bank_account;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }
}

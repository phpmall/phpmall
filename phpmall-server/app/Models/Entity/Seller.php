<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Focite\Builder\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerSchema')]
class Seller
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '卖家创始人ID', type: 'int')]
    protected int $userId;

    #[OA\Property(property: 'company_name', description: '企业名称', type: 'string')]
    protected string $companyName;

    #[OA\Property(property: 'company_address', description: '企业地址', type: 'string')]
    protected string $companyAddress;

    #[OA\Property(property: 'legal_person', description: '企业法人姓名', type: 'string')]
    protected string $legalPerson;

    #[OA\Property(property: 'business_license', description: '企业营业执照号', type: 'string')]
    protected string $businessLicense;

    #[OA\Property(property: 'tax_registration', description: '企业税务登记号', type: 'string')]
    protected string $taxRegistration;

    #[OA\Property(property: 'opening_bank', description: '开户银行', type: 'string')]
    protected string $openingBank;

    #[OA\Property(property: 'bank_account', description: '企业银行账户', type: 'string')]
    protected string $bankAccount;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deletedAt;

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
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置卖家创始人ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取企业名称
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * 设置企业名称
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * 获取企业地址
     */
    public function getCompanyAddress(): string
    {
        return $this->companyAddress;
    }

    /**
     * 设置企业地址
     */
    public function setCompanyAddress(string $companyAddress): void
    {
        $this->companyAddress = $companyAddress;
    }

    /**
     * 获取企业法人姓名
     */
    public function getLegalPerson(): string
    {
        return $this->legalPerson;
    }

    /**
     * 设置企业法人姓名
     */
    public function setLegalPerson(string $legalPerson): void
    {
        $this->legalPerson = $legalPerson;
    }

    /**
     * 获取企业营业执照号
     */
    public function getBusinessLicense(): string
    {
        return $this->businessLicense;
    }

    /**
     * 设置企业营业执照号
     */
    public function setBusinessLicense(string $businessLicense): void
    {
        $this->businessLicense = $businessLicense;
    }

    /**
     * 获取企业税务登记号
     */
    public function getTaxRegistration(): string
    {
        return $this->taxRegistration;
    }

    /**
     * 设置企业税务登记号
     */
    public function setTaxRegistration(string $taxRegistration): void
    {
        $this->taxRegistration = $taxRegistration;
    }

    /**
     * 获取开户银行
     */
    public function getOpeningBank(): string
    {
        return $this->openingBank;
    }

    /**
     * 设置开户银行
     */
    public function setOpeningBank(string $openingBank): void
    {
        $this->openingBank = $openingBank;
    }

    /**
     * 获取企业银行账户
     */
    public function getBankAccount(): string
    {
        return $this->bankAccount;
    }

    /**
     * 设置企业银行账户
     */
    public function setBankAccount(string $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\User\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAccountEntity')]
class UserAccountEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getUserId = 'user_id'; // 用户ID

    const string getAdminUser = 'admin_user'; // 管理员

    const string getAmount = 'amount'; // 金额

    const string getAddTime = 'add_time'; // 添加时间

    const string getPaidTime = 'paid_time'; // 支付时间

    const string getAdminNote = 'admin_note'; // 管理员备注

    const string getUserNote = 'user_note'; // 用户备注

    const string getProcessType = 'process_type'; // 处理类型

    const string getPayment = 'payment'; // 支付方式

    const string getIsPaid = 'is_paid'; // 是否已支付

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'adminUser', description: '管理员', type: 'string')]
    private string $adminUser;

    #[OA\Property(property: 'amount', description: '金额', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'addTime', description: '添加时间', type: 'integer')]
    private int $addTime;

    #[OA\Property(property: 'paidTime', description: '支付时间', type: 'integer')]
    private int $paidTime;

    #[OA\Property(property: 'adminNote', description: '管理员备注', type: 'string')]
    private string $adminNote;

    #[OA\Property(property: 'userNote', description: '用户备注', type: 'string')]
    private string $userNote;

    #[OA\Property(property: 'processType', description: '处理类型', type: 'integer')]
    private int $processType;

    #[OA\Property(property: 'payment', description: '支付方式', type: 'string')]
    private string $payment;

    #[OA\Property(property: 'isPaid', description: '是否已支付', type: 'integer')]
    private int $isPaid;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取管理员
     */
    public function getAdminUser(): string
    {
        return $this->adminUser;
    }

    /**
     * 设置管理员
     */
    public function setAdminUser(string $adminUser): void
    {
        $this->adminUser = $adminUser;
    }

    /**
     * 获取金额
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置金额
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取添加时间
     */
    public function getAddTime(): int
    {
        return $this->addTime;
    }

    /**
     * 设置添加时间
     */
    public function setAddTime(int $addTime): void
    {
        $this->addTime = $addTime;
    }

    /**
     * 获取支付时间
     */
    public function getPaidTime(): int
    {
        return $this->paidTime;
    }

    /**
     * 设置支付时间
     */
    public function setPaidTime(int $paidTime): void
    {
        $this->paidTime = $paidTime;
    }

    /**
     * 获取管理员备注
     */
    public function getAdminNote(): string
    {
        return $this->adminNote;
    }

    /**
     * 设置管理员备注
     */
    public function setAdminNote(string $adminNote): void
    {
        $this->adminNote = $adminNote;
    }

    /**
     * 获取用户备注
     */
    public function getUserNote(): string
    {
        return $this->userNote;
    }

    /**
     * 设置用户备注
     */
    public function setUserNote(string $userNote): void
    {
        $this->userNote = $userNote;
    }

    /**
     * 获取处理类型
     */
    public function getProcessType(): int
    {
        return $this->processType;
    }

    /**
     * 设置处理类型
     */
    public function setProcessType(int $processType): void
    {
        $this->processType = $processType;
    }

    /**
     * 获取支付方式
     */
    public function getPayment(): string
    {
        return $this->payment;
    }

    /**
     * 设置支付方式
     */
    public function setPayment(string $payment): void
    {
        $this->payment = $payment;
    }

    /**
     * 获取是否已支付
     */
    public function getIsPaid(): int
    {
        return $this->isPaid;
    }

    /**
     * 设置是否已支付
     */
    public function setIsPaid(int $isPaid): void
    {
        $this->isPaid = $isPaid;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedTime(): string
    {
        return $this->createdTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedTime(string $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedTime(string $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}

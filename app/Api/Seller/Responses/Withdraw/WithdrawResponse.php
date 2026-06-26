<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Withdraw;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerWithdrawResponse')]
class WithdrawResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '提现ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'withdraw_no', description: '提现单号', type: 'string')]
    private string $withdrawNo;

    #[OA\Property(property: 'amount', description: '提现金额(分)', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'fee', description: '手续费(分)', type: 'integer')]
    private int $fee;

    #[OA\Property(property: 'actual_amount', description: '实际到账金额(分)', type: 'integer')]
    private int $actualAmount;

    #[OA\Property(property: 'account_type', description: '账户类型:1银行卡,2支付宝,3微信', type: 'integer')]
    private int $accountType;

    #[OA\Property(property: 'account_info', description: '账户信息', type: 'object')]
    private array $accountInfo;

    #[OA\Property(property: 'status', description: '状态:0待审核,1审核通过,2审核拒绝,3处理中,4已完成,5失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'remark', description: '备注', type: 'string', nullable: true)]
    private ?string $remark;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'processed_at', description: '处理时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $processedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getWithdrawNo(): string
    {
        return $this->withdrawNo;
    }

    public function setWithdrawNo(string $withdrawNo): void
    {
        $this->withdrawNo = $withdrawNo;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getFee(): int
    {
        return $this->fee;
    }

    public function setFee(int $fee): void
    {
        $this->fee = $fee;
    }

    public function getActualAmount(): int
    {
        return $this->actualAmount;
    }

    public function setActualAmount(int $actualAmount): void
    {
        $this->actualAmount = $actualAmount;
    }

    public function getAccountType(): int
    {
        return $this->accountType;
    }

    public function setAccountType(int $accountType): void
    {
        $this->accountType = $accountType;
    }

    public function getAccountInfo(): array
    {
        return $this->accountInfo;
    }

    public function setAccountInfo(array $accountInfo): void
    {
        $this->accountInfo = $accountInfo;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getProcessedAt(): ?string
    {
        return $this->processedAt;
    }

    public function setProcessedAt(?string $processedAt): void
    {
        $this->processedAt = $processedAt;
    }
}

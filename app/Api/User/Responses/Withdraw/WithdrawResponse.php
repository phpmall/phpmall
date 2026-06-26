<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Withdraw;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WithdrawResponse')]
class WithdrawResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '提现记录ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'withdraw_no', description: '提现单号', type: 'string')]
    private string $withdrawNo;

    #[OA\Property(property: 'amount', description: '提现金额(分)', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'fee', description: '手续费(分)', type: 'integer', nullable: true)]
    private ?int $fee;

    #[OA\Property(property: 'actual_amount', description: '实际到账金额(分)', type: 'integer')]
    private int $actualAmount;

    #[OA\Property(property: 'method', description: '提现方式:alipay,wechat,bank', type: 'string')]
    private string $method;

    #[OA\Property(property: 'account', description: '提现账号', type: 'string')]
    private string $account;

    #[OA\Property(property: 'real_name', description: '真实姓名', type: 'string', nullable: true)]
    private ?string $realName;

    #[OA\Property(property: 'status', description: '状态:0待审核,1审核通过,2审核拒绝,3处理中,4已完成,5失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'remark', description: '备注', type: 'string', nullable: true)]
    private ?string $remark;

    #[OA\Property(property: 'reject_reason', description: '拒绝原因', type: 'string', nullable: true)]
    private ?string $rejectReason;

    #[OA\Property(property: 'created_at', description: '申请时间', type: 'string', format: 'date-time')]
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

    public function getFee(): ?int
    {
        return $this->fee;
    }

    public function setFee(?int $fee): void
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

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getAccount(): string
    {
        return $this->account;
    }

    public function setAccount(string $account): void
    {
        $this->account = $account;
    }

    public function getRealName(): ?string
    {
        return $this->realName;
    }

    public function setRealName(?string $realName): void
    {
        $this->realName = $realName;
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

    public function getRejectReason(): ?string
    {
        return $this->rejectReason;
    }

    public function setRejectReason(?string $rejectReason): void
    {
        $this->rejectReason = $rejectReason;
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

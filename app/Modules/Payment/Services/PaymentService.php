<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Repositories\PaymentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class PaymentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly PaymentRepository $repository,
    ) {}

    public function getRepository(): PaymentRepository
    {
        return $this->repository;
    }

    /**
     * 创建支付记录
     *
     * @param  array  $data  支付数据
     */
    public function createPayment(array $data): Payment
    {
        return Payment::create([
            'payment_no' => $data['payment_no'],
            'order_id' => $data['order_id'],
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            'channel' => $data['channel'],
            'status' => $data['status'] ?? 0,
            'client_ip' => $data['client_ip'] ?? null,
            'expired_at' => $data['expired_at'],
        ]);
    }

    /**
     * 根据ID和用户ID查询支付记录
     */
    public function findByIdAndUserId(int $id, int $userId): ?Payment
    {
        return Payment::where('id', $id)->where('user_id', $userId)->first();
    }
}

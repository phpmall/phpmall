<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Modules\Order\Repositories\OrderRefundRepository;
use App\Modules\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Services\CommonService;

class OrderRefundService extends CommonService implements ServiceInterface
{
    private const int TYPE_REFUND = 1;

    private const int TYPE_RETURN_REFUND = 2;

    private const int STATUS_PENDING = 0;

    private const int STATUS_APPROVED = 1;

    private const int STATUS_REJECTED = 2;

    private const int STATUS_RETURNING = 3;

    private const int STATUS_PLATFORM = 4;

    private const int STATUS_REFUNDED = 5;

    private const int STATUS_CLOSED = 6;

    private const int STATUS_CANCELED = 7;

    private const int ORDER_PAY_STATUS_PAID = 20;

    private const int ORDER_REFUND_STATUS_APPLYING = 10;

    private const int ORDER_REFUND_STATUS_REFUNDING = 20;

    private const int ORDER_REFUND_STATUS_REFUSED = 40;

    public function __construct(
        private readonly OrderRefundRepository $repository,
        private readonly OrderRepository $orderRepository,
    ) {}

    public function getRepository(): OrderRefundRepository
    {
        return $this->repository;
    }

    /**
     * 用户申请退款
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function apply(int $userId, array $data): array
    {
        $orderId = (int) ($data['order_id'] ?? 0);
        $order = $this->orderRepository->findById($orderId);

        if (empty($order) || (int) $order['user_id'] !== $userId) {
            throw new BusinessException('订单不存在');
        }

        if ((int) $order['pay_status'] < self::ORDER_PAY_STATUS_PAID) {
            throw new BusinessException('订单未支付，无法申请退款');
        }

        $applyAmount = isset($data['amount']) ? (int) $data['amount'] : (int) $order['pay_amount'];
        if ($applyAmount <= 0 || $applyAmount > (int) $order['pay_amount']) {
            throw new BusinessException('退款金额不合法');
        }

        $type = match ($data['type'] ?? '') {
            'refund' => self::TYPE_REFUND,
            'return_refund' => self::TYPE_RETURN_REFUND,
            default => throw new BusinessException('退款类型不合法'),
        };

        $images = $data['images'] ?? [];
        $refundNo = $this->generateRefundNo();

        $refundId = DB::transaction(function () use (
            $orderId,
            $userId,
            $order,
            $type,
            $data,
            $applyAmount,
            $images,
            $refundNo
        ): int {
            $refundId = $this->repository->save([
                'refund_no' => $refundNo,
                'order_id' => $orderId,
                'order_item_id' => $data['order_item_id'] ?? null,
                'user_id' => $userId,
                'merchant_id' => (int) $order['merchant_id'],
                'type' => $type,
                'reason' => (string) ($data['reason'] ?? ''),
                'reason_type' => 1,
                'description' => $data['description'] ?? null,
                'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
                'apply_amount' => $applyAmount,
                'refund_amount' => 0,
                'status' => self::STATUS_PENDING,
                'merchant_remark' => null,
                'platform_remark' => null,
                'return_express_company' => null,
                'return_express_no' => null,
                'return_ship_time' => null,
                'merchant_receipt_time' => null,
                'refund_time' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->orderRepository->updateById([
                'refund_status' => self::ORDER_REFUND_STATUS_APPLYING,
            ], $orderId);

            return $refundId;
        });

        return $this->findRefundWithOrderNo($refundId);
    }

    /**
     * 查询用户退款列表
     *
     * @return array<string, mixed>
     */
    public function getUserRefunds(int $userId, ?int $status, int $page, int $perPage): array
    {
        $query = $this->repository->builder()
            ->leftJoin('orders', 'order_refunds.order_id', '=', 'orders.id')
            ->where('order_refunds.user_id', $userId)
            ->select(['order_refunds.*', 'orders.order_no']);

        if ($status !== null) {
            $query->where('order_refunds.status', $status);
        }

        $result = $query->orderByDesc('order_refunds.id')
            ->paginate($perPage, ['order_refunds.*', 'orders.order_no'], 'page', $page);

        return $this->formatPageResult($result);
    }

    /**
     * 查询用户退款详情
     *
     * @return array<string, mixed>
     */
    public function getUserRefundDetail(int $userId, int $refundId): array
    {
        $refund = $this->findRefundWithOrderNo($refundId);

        if (empty($refund) || (int) $refund['user_id'] !== $userId) {
            throw new BusinessException('退款记录不存在');
        }

        return $refund;
    }

    /**
     * 用户撤销退款申请
     */
    public function cancelByUser(int $userId, int $refundId): bool
    {
        $refund = $this->repository->findById($refundId);

        if (empty($refund) || (int) $refund['user_id'] !== $userId) {
            throw new BusinessException('退款记录不存在');
        }

        if ((int) $refund['status'] !== self::STATUS_PENDING) {
            throw new BusinessException('只有待审核的退款可以撤销');
        }

        return DB::transaction(function () use ($refundId, $refund): bool {
            $this->repository->updateById([
                'status' => self::STATUS_CANCELED,
                'updated_at' => now(),
            ], $refundId);

            $this->resetOrderRefundStatus((int) $refund['order_id']);

            return true;
        });
    }

    /**
     * 查询商家退款列表
     *
     * @return array<string, mixed>
     */
    public function getMerchantRefunds(int $merchantId, ?int $status, int $page, int $perPage): array
    {
        $query = $this->repository->builder()
            ->leftJoin('orders', 'order_refunds.order_id', '=', 'orders.id')
            ->where('order_refunds.merchant_id', $merchantId)
            ->select(['order_refunds.*', 'orders.order_no']);

        if ($status !== null) {
            $query->where('order_refunds.status', $status);
        }

        $result = $query->orderByDesc('order_refunds.id')
            ->paginate($perPage, ['order_refunds.*', 'orders.order_no'], 'page', $page);

        return $this->formatPageResult($result);
    }

    /**
     * 查询商家退款详情
     *
     * @return array<string, mixed>
     */
    public function getMerchantRefundDetail(int $merchantId, int $refundId): array
    {
        $refund = $this->findRefundWithOrderNo($refundId);

        if (empty($refund) || (int) $refund['merchant_id'] !== $merchantId) {
            throw new BusinessException('退款记录不存在');
        }

        return $refund;
    }

    /**
     * 商家审核退款
     *
     * @param  int  $auditStatus  1=通过,2=拒绝
     */
    public function audit(int $merchantId, int $refundId, int $auditStatus, ?string $remark): bool
    {
        $refund = $this->repository->findById($refundId);

        if (empty($refund) || (int) $refund['merchant_id'] !== $merchantId) {
            throw new BusinessException('退款记录不存在');
        }

        if ((int) $refund['status'] !== self::STATUS_PENDING) {
            throw new BusinessException('该退款已处理');
        }

        if ($auditStatus === 1) {
            return $this->approveRefund($refundId, $refund, $remark);
        }

        if ($auditStatus === 2) {
            return $this->rejectRefund($refundId, $refund, $remark);
        }

        throw new BusinessException('审核状态不合法');
    }

    /**
     * 审批通过
     *
     * @param  array<string, mixed>  $refund
     */
    private function approveRefund(int $refundId, array $refund, ?string $remark): bool
    {
        return DB::transaction(function () use ($refundId, $refund, $remark): bool {
            $this->repository->updateById([
                'status' => self::STATUS_APPROVED,
                'refund_amount' => (int) $refund['apply_amount'],
                'merchant_remark' => $remark,
                'refund_time' => now(),
                'updated_at' => now(),
            ], $refundId);

            $this->orderRepository->updateById([
                'refund_status' => self::ORDER_REFUND_STATUS_REFUNDING,
            ], (int) $refund['order_id']);

            return true;
        });
    }

    /**
     * 审批拒绝
     *
     * @param  array<string, mixed>  $refund
     */
    private function rejectRefund(int $refundId, array $refund, ?string $remark): bool
    {
        return DB::transaction(function () use ($refundId, $refund, $remark): bool {
            $this->repository->updateById([
                'status' => self::STATUS_REJECTED,
                'merchant_remark' => $remark,
                'updated_at' => now(),
            ], $refundId);

            $this->orderRepository->updateById([
                'refund_status' => self::ORDER_REFUND_STATUS_REFUSED,
            ], (int) $refund['order_id']);

            return true;
        });
    }

    /**
     * 查询退款记录并附带订单号
     *
     * @return array<string, mixed>
     */
    private function findRefundWithOrderNo(int $refundId): array
    {
        $refund = $this->repository->findById($refundId);

        if (empty($refund)) {
            return [];
        }

        $order = $this->orderRepository->findById((int) $refund['order_id']);
        $refund['order_no'] = $order['order_no'] ?? '';

        return $refund;
    }

    /**
     * 重置订单退款状态为无退款
     */
    private function resetOrderRefundStatus(int $orderId): void
    {
        $pendingCount = $this->repository->builder()
            ->where('order_id', $orderId)
            ->where('status', self::STATUS_PENDING)
            ->count();

        if ($pendingCount === 0) {
            $this->orderRepository->updateById([
                'refund_status' => 0,
            ], $orderId);
        }
    }

    /**
     * 格式化分页结果
     *
     * @return array<string, mixed>
     */
    private function formatPageResult(mixed $result): array
    {
        $data = $result->toArray();
        $total = (int) ($data['total'] ?? 0);
        $lastPage = (int) ($data['last_page'] ?? 1);
        $currentPage = (int) ($data['current_page'] ?? 1);

        $items = [];
        foreach ($data['data'] ?? [] as $item) {
            $items[] = (array) $item;
        }

        return [
            'items' => $items,
            'pagination' => [
                'page' => $currentPage,
                'per_page' => (int) ($data['per_page'] ?? 20),
                'total' => $total,
                'total_pages' => $lastPage,
                'has_next' => $currentPage < $lastPage,
                'has_prev' => $currentPage > 1,
            ],
        ];
    }

    /**
     * 生成退款单号
     */
    private function generateRefundNo(): string
    {
        return 'R'.date('YmdHis').str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }
}

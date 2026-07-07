<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Order\Repositories\OrderShipmentRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Services\CommonService;

class OrderShipmentService extends CommonService implements ServiceInterface
{
    private const int STATUS_PAID = 20;

    private const int STATUS_PENDING_SHIPMENT = 30;

    private const int STATUS_SHIPPED = 40;

    public function __construct(
        private readonly OrderRepository $repository,
        private readonly OrderShipmentRepository $shipmentRepository,
    ) {}

    public function getRepository(): OrderRepository
    {
        return $this->repository;
    }

    /**
     * 订单发货
     *
     * @param  array<string, mixed>  $data
     */
    public function ship(int $orderId, int $merchantId, array $data): void
    {
        $order = $this->repository->findById($orderId);

        if (empty($order) || (int) $order['merchant_id'] !== $merchantId) {
            throw new BusinessException('订单不存在');
        }

        $status = (int) $order['status'];
        if (! in_array($status, [self::STATUS_PAID, self::STATUS_PENDING_SHIPMENT], true)) {
            throw new BusinessException('订单状态不可发货');
        }

        DB::transaction(function () use ($orderId, $merchantId, $data): void {
            $this->shipmentRepository->save([
                'order_id' => $orderId,
                'merchant_id' => $merchantId,
                'logistics_company' => $data['logistics_company'],
                'tracking_no' => $data['tracking_no'],
                'remark' => $data['remark'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->repository->updateById([
                'status' => self::STATUS_SHIPPED,
                'ship_time' => now(),
            ], $orderId);
        });
    }
}

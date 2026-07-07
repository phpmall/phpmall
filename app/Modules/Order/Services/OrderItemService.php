<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Modules\Order\Repositories\OrderItemRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderItemService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderItemRepository $repository,
    ) {}

    public function getRepository(): OrderItemRepository
    {
        return $this->repository;
    }

    /**
     * 创建订单商品项
     *
     * @param  array<string, mixed>  $item
     */
    public function createItem(int $orderId, int $merchantId, array $item): int
    {
        return $this->repository->save([
            'order_id' => $orderId,
            'product_id' => (int) ($item['product_id'] ?? 0),
            'sku_id' => (int) $item['sku_id'],
            'merchant_id' => $merchantId,
            'product_title' => $item['product_name'] ?? $item['product_title'] ?? '',
            'product_image' => $item['image'] ?? $item['product_image'] ?? '',
            'sku_specs' => $item['sku_specs'] ?? null,
            'price' => (int) $item['price'],
            'quantity' => (int) $item['quantity'],
            'total_amount' => (int) ($item['total_price'] ?? $item['price'] * $item['quantity']),
            'discount_amount' => 0,
            'refund_amount' => 0,
            'refund_status' => 0,
            'is_commented' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * 根据订单ID获取商品项列表
     *
     * @return array<int, array<string, mixed>>
     */
    public function getItemsByOrderId(int $orderId): array
    {
        $items = DB::table('order_items')
            ->where('order_id', $orderId)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];
        foreach ($items as $item) {
            $item = (array) $item;
            $result[] = [
                'id' => (int) $item['id'],
                'product_name' => $item['product_title'],
                'sku_name' => $this->formatSkuSpecs($item['sku_specs']),
                'image' => $item['product_image'],
                'price' => (int) $item['price'],
                'quantity' => (int) $item['quantity'],
                'total_price' => (int) $item['total_amount'],
            ];
        }

        return $result;
    }

    /**
     * 根据订单ID获取商家端商品项列表
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSellerItemsByOrderId(int $orderId): array
    {
        $items = $this->repository->builder()
            ->where('order_id', $orderId)
            ->orderBy('id', 'asc')
            ->get();

        $result = [];
        foreach ($items as $item) {
            $item = (array) $item;
            $result[] = [
                'id' => (int) $item['id'],
                'product_id' => (int) $item['product_id'],
                'product_name' => $item['product_title'],
                'product_image' => $item['product_image'],
                'sku_id' => (int) $item['sku_id'],
                'sku_spec' => $this->formatSkuSpecs($item['sku_specs']),
                'price' => (int) $item['price'],
                'quantity' => (int) $item['quantity'],
                'total_amount' => (int) $item['total_amount'],
            ];
        }

        return $result;
    }

    /**
     * 格式化 SKU 规格
     */
    private function formatSkuSpecs(mixed $specs): ?string
    {
        if (empty($specs)) {
            return null;
        }

        if (is_string($specs)) {
            $specs = json_decode($specs, true);
        }

        if (! is_array($specs)) {
            return null;
        }

        $parts = [];
        foreach ($specs as $spec) {
            if (is_array($spec) && isset($spec['value'])) {
                $parts[] = (string) $spec['value'];
            }
        }

        return empty($parts) ? null : implode(' / ', $parts);
    }
}

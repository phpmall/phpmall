<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Api\Seller\Responses\ProductSku\ProductSkuResponse;
use App\Modules\Product\Models\ProductSku;
use App\Modules\Product\Repositories\ProductSkuRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductSkuService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductSkuRepository $repository,
    ) {}

    public function getRepository(): ProductSkuRepository
    {
        return $this->repository;
    }

    /**
     * 分页查询商家 SKU 列表
     */
    public function paginateByMerchantId(int $merchantId, array $params = []): array
    {
        $condition = ['merchant_id' => $merchantId];

        if (! empty($params['product_id'])) {
            $condition['product_id'] = (int) $params['product_id'];
        }

        $page = (int) ($params['page'] ?? 1);
        $perPage = (int) ($params['per_page'] ?? 20);

        $result = $this->getRepository()->page($condition, $page, $perPage);

        if (! empty($result['data'])) {
            $result['data'] = array_map(
                fn (array $item): ProductSkuResponse => $this->toResponse($item),
                $result['data']
            );
        }

        return $this->formatPage($result);
    }

    /**
     * 创建 SKU
     */
    public function createForMerchant(int $merchantId, array $data): ProductSku
    {
        $skuData = $this->mapStoreData($merchantId, $data);
        $skuId = $this->insertGetId($skuData);

        $this->syncProductPriceAndStock($data['product_id'], $merchantId);

        return ProductSku::findOrFail($skuId);
    }

    /**
     * 更新 SKU
     */
    public function updateForMerchant(int $id, int $merchantId, array $data): ProductSku
    {
        $sku = ProductSku::where('id', $id)->where('merchant_id', $merchantId)->firstOrFail();
        $sku->update($this->mapUpdateData($data));

        $this->syncProductPriceAndStock($sku->product_id, $merchantId);

        return $sku->fresh();
    }

    /**
     * 删除 SKU
     */
    public function deleteForMerchant(int $id, int $merchantId): bool
    {
        $sku = ProductSku::where('id', $id)->where('merchant_id', $merchantId)->first();

        if ($sku === null) {
            return false;
        }

        $productId = $sku->product_id;
        $deleted = (bool) $sku->delete();

        $this->syncProductPriceAndStock($productId, $merchantId);

        return $deleted;
    }

    /**
     * 批量更新 SKU
     */
    public function batchUpdate(int $merchantId, array $items): bool
    {
        if (empty($items)) {
            return false;
        }

        $productIds = [];
        foreach ($items as $item) {
            ProductSku::where('id', $item['id'])
                ->where('merchant_id', $merchantId)
                ->update([
                    'sku_code' => $item['sku_code'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'image' => $item['image'] ?? null,
                    'updated_at' => now(),
                ]);

            $sku = ProductSku::where('id', $item['id'])->where('merchant_id', $merchantId)->first();
            if ($sku !== null) {
                $productIds[] = $sku->product_id;
            }
        }

        foreach (array_unique($productIds) as $productId) {
            $this->syncProductPriceAndStock($productId, $merchantId);
        }

        return true;
    }

    /**
     * 将 SKU 数据转换为响应 DTO
     */
    public function toResponse(array $sku): ProductSkuResponse
    {
        return ProductSkuResponse::from([
            'id' => (int) $sku['id'],
            'product_id' => (int) $sku['product_id'],
            'sku_code' => $sku['sku_code'],
            'price' => (int) $sku['price'],
            'stock' => (int) $sku['stock'],
            'attributes' => $this->decodeJson($sku['sku_specs'] ?? '[]'),
            'image' => $sku['image'] ?? null,
            'created_at' => $sku['created_at'],
            'updated_at' => $sku['updated_at'],
        ]);
    }

    /**
     * 同步商品的最低/最高售价与总库存
     */
    public function syncProductPriceAndStock(int $productId, int $merchantId): void
    {
        $skus = ProductSku::where('product_id', $productId)->where('merchant_id', $merchantId)->where('status', 1)->get();

        if ($skus->isEmpty()) {
            return;
        }

        $minPrice = $skus->min('price');
        $maxPrice = $skus->max('price');
        $totalStock = $skus->sum('stock');

        DB::table('products')
            ->where('id', $productId)
            ->where('merchant_id', $merchantId)
            ->update([
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'total_stock' => $totalStock,
                'updated_at' => now(),
            ]);
    }

    private function formatPage(array $page): array
    {
        return [
            'items' => $page['data'] ?? [],
            'pagination' => [
                'page' => (int) ($page['current_page'] ?? 1),
                'per_page' => (int) ($page['per_page'] ?? 20),
                'total' => (int) ($page['total'] ?? 0),
                'total_pages' => (int) ($page['last_page'] ?? 1),
                'has_next' => ! empty($page['next_page_url']),
                'has_prev' => ! empty($page['prev_page_url']),
            ],
        ];
    }

    private function mapStoreData(int $merchantId, array $data): array
    {
        return [
            'product_id' => $data['product_id'],
            'merchant_id' => $merchantId,
            'sku_code' => $data['sku_code'],
            'sku_specs' => json_encode($data['attributes'] ?? [], JSON_UNESCAPED_UNICODE),
            'price' => $data['price'],
            'market_price' => $data['market_price'] ?? $data['price'],
            'cost_price' => $data['cost_price'] ?? 0,
            'stock' => $data['stock'],
            'stock_alarm' => $data['stock_alarm'] ?? 10,
            'weight' => $data['weight'] ?? 0,
            'image' => $data['image'] ?? null,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function mapUpdateData(array $data): array
    {
        $update = [
            'sku_code' => $data['sku_code'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'updated_at' => now(),
        ];

        if (isset($data['attributes'])) {
            $update['sku_specs'] = json_encode($data['attributes'], JSON_UNESCAPED_UNICODE);
        }

        if (array_key_exists('image', $data)) {
            $update['image'] = $data['image'];
        }

        return $update;
    }

    private function decodeJson(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($value) ? $value : [];
    }
}

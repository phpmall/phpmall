<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Api\Seller\Responses\Product\ProductResponse;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductRepository $repository,
    ) {}

    public function getRepository(): ProductRepository
    {
        return $this->repository;
    }

    /**
     * 获取推荐商品
     */
    public function getRecommendProducts(int $limit = 10): array
    {
        $products = $this->repository->builder()
            ->where([
                'status' => 1,
                'audit_status' => 1,
                'is_recommend' => 1,
            ])
            ->orderByDesc('sort_order')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn ($product): array => (array) $product)
            ->all();

        return array_map(fn (array $product): array => $this->formatProduct($product), $products);
    }

    /**
     * 分页查询商家商品列表
     */
    public function paginateByMerchantId(int $merchantId, array $params = []): array
    {
        $condition = ['merchant_id' => $merchantId];

        if (! empty($params['status'])) {
            $condition['status'] = (int) $params['status'];
        }

        if (! empty($params['category_id'])) {
            $condition['category_id'] = (int) $params['category_id'];
        }

        $page = (int) ($params['page'] ?? 1);
        $perPage = (int) ($params['per_page'] ?? 20);

        $result = $this->getRepository()->page($condition, $page, $perPage, 'sort_order', 'desc');

        if (! empty($result['data'])) {
            $result['data'] = array_map(
                fn (array $item): ProductResponse => $this->toResponse($item),
                $result['data']
            );
        }

        return $this->formatPage($result);
    }

    /**
     * 创建商品
     */
    public function createForMerchant(int $merchantId, array $data): Product
    {
        return DB::transaction(function () use ($merchantId, $data): Product {
            $productData = $this->mapStoreData($merchantId, $data);
            $productId = $this->insertGetId($productData);

            if (! empty($data['skus'])) {
                $this->createSkus($productId, $merchantId, $data['skus']);
                $this->syncPriceAndStockFromSkus($productId, $merchantId);
            }

            return Product::findOrFail($productId)->fresh();
        });
    }

    /**
     * 更新商品
     */
    public function updateForMerchant(int $id, int $merchantId, array $data): Product
    {
        return DB::transaction(function () use ($id, $merchantId, $data): Product {
            $product = Product::where('id', $id)->where('merchant_id', $merchantId)->firstOrFail();
            $product->update($this->mapUpdateData($data));

            if (! empty($data['skus'])) {
                $this->syncSkus($product, $merchantId, $data['skus']);
            }

            return $product->fresh();
        });
    }

    /**
     * 查询商家商品详情
     */
    public function findForMerchant(int $id, int $merchantId): ?Product
    {
        return Product::where('id', $id)->where('merchant_id', $merchantId)->first();
    }

    /**
     * 删除商家商品
     */
    public function deleteForMerchant(int $id, int $merchantId): bool
    {
        $product = Product::where('id', $id)->where('merchant_id', $merchantId)->first();

        if ($product === null) {
            return false;
        }

        return (bool) $product->delete();
    }

    /**
     * 更新商品状态
     */
    public function updateStatus(int $id, int $merchantId, int $status): bool
    {
        $affected = Product::where('id', $id)->where('merchant_id', $merchantId)->update(['status' => $status]);

        return $affected > 0;
    }

    /**
     * 批量更新商品状态
     */
    public function batchUpdateStatus(array $ids, int $merchantId, int $status): bool
    {
        if (empty($ids)) {
            return false;
        }

        Product::where('merchant_id', $merchantId)->whereIn('id', $ids)->update(['status' => $status]);

        return true;
    }

    /**
     * 批量删除商品
     */
    public function batchDelete(array $ids, int $merchantId): bool
    {
        if (empty($ids)) {
            return false;
        }

        Product::where('merchant_id', $merchantId)->whereIn('id', $ids)->delete();

        return true;
    }

    /**
     * 将商品数据转换为响应 DTO
     */
    public function toResponse(array $product): ProductResponse
    {
        return ProductResponse::from([
            'id' => (int) $product['id'],
            'name' => $product['title'],
            'description' => $product['description'] ?? null,
            'category_id' => (int) $product['category_id'],
            'brand_id' => null,
            'shop_category_id' => null,
            'price' => (int) $product['min_price'],
            'market_price' => (int) $product['max_price'],
            'cost_price' => (int) ($product['cost_price'] ?? 0),
            'stock' => (int) $product['total_stock'],
            'status' => (int) $product['status'],
            'images' => $this->decodeJson($product['images'] ?? '[]'),
            'attributes' => $this->decodeJson($product['attributes'] ?? '[]'),
            'skus' => [],
            'created_at' => $product['created_at'],
            'updated_at' => $product['updated_at'],
        ]);
    }

    private function formatProduct(array $product): array
    {
        return [
            'id' => (int) $product['id'],
            'name' => $product['title'],
            'subtitle' => $product['subtitle'] ?? null,
            'mainImage' => $product['main_image'],
            'images' => $this->decodeImages($product['images']),
            'price' => (int) $product['min_price'],
            'marketPrice' => (int) $product['max_price'],
            'stock' => (int) $product['total_stock'],
            'soldCount' => (int) $product['sales_count'],
            'isHot' => (int) $product['is_hot'],
            'isRecommend' => (int) $product['is_recommend'],
            'status' => (int) $product['status'],
            'createdAt' => $product['created_at'],
            'updatedAt' => $product['updated_at'],
        ];
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
        $images = $data['images'] ?? [];

        return [
            'merchant_id' => $merchantId,
            'category_id' => $data['category_id'],
            'title' => $data['name'],
            'subtitle' => null,
            'description' => $data['description'] ?? null,
            'main_image' => $images[0] ?? '',
            'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
            'status' => $data['status'] ?? 0,
            'audit_status' => 1,
            'min_price' => $data['price'],
            'max_price' => $data['market_price'] ?? $data['price'],
            'cost_price' => $data['cost_price'] ?? 0,
            'total_stock' => $data['stock'],
            'attributes' => json_encode($data['attributes'] ?? [], JSON_UNESCAPED_UNICODE),
            'stock_type' => empty($data['skus']) ? 1 : 2,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function mapUpdateData(array $data): array
    {
        $update = [
            'category_id' => $data['category_id'],
            'title' => $data['name'],
            'description' => $data['description'] ?? null,
            'min_price' => $data['price'],
            'max_price' => $data['market_price'] ?? $data['price'],
            'cost_price' => $data['cost_price'] ?? 0,
            'total_stock' => $data['stock'],
            'status' => $data['status'] ?? 0,
            'updated_at' => now(),
        ];

        if (isset($data['images'])) {
            $images = $data['images'];
            $update['main_image'] = $images[0] ?? '';
            $update['images'] = json_encode($images, JSON_UNESCAPED_UNICODE);
        }

        if (isset($data['attributes'])) {
            $update['attributes'] = json_encode($data['attributes'], JSON_UNESCAPED_UNICODE);
        }

        if (isset($data['skus'])) {
            $update['stock_type'] = empty($data['skus']) ? 1 : 2;
        }

        return $update;
    }

    private function createSkus(int $productId, int $merchantId, array $skus): void
    {
        $rows = [];
        foreach ($skus as $sku) {
            $rows[] = [
                'product_id' => $productId,
                'merchant_id' => $merchantId,
                'sku_code' => $sku['sku_code'],
                'sku_specs' => json_encode($sku['attributes'] ?? [], JSON_UNESCAPED_UNICODE),
                'price' => $sku['price'],
                'market_price' => $sku['market_price'] ?? $sku['price'],
                'cost_price' => $sku['cost_price'] ?? 0,
                'stock' => $sku['stock'],
                'stock_alarm' => $sku['stock_alarm'] ?? 10,
                'weight' => $sku['weight'] ?? 0,
                'image' => $sku['image'] ?? null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (! empty($rows)) {
            DB::table('product_skus')->insert($rows);
        }
    }

    private function syncSkus(Product $product, int $merchantId, array $skus): void
    {
        $existingIds = [];
        foreach ($skus as $sku) {
            if (! empty($sku['id'])) {
                $existingIds[] = $sku['id'];
            }
        }

        if (! empty($existingIds)) {
            DB::table('product_skus')
                ->where('product_id', $product->id)
                ->where('merchant_id', $merchantId)
                ->whereNotIn('id', $existingIds)
                ->delete();
        }

        foreach ($skus as $sku) {
            if (! empty($sku['id'])) {
                DB::table('product_skus')
                    ->where('id', $sku['id'])
                    ->where('merchant_id', $merchantId)
                    ->update([
                        'sku_code' => $sku['sku_code'],
                        'sku_specs' => json_encode($sku['attributes'] ?? [], JSON_UNESCAPED_UNICODE),
                        'price' => $sku['price'],
                        'market_price' => $sku['market_price'] ?? $sku['price'],
                        'cost_price' => $sku['cost_price'] ?? 0,
                        'stock' => $sku['stock'],
                        'image' => $sku['image'] ?? null,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('product_skus')->insert([
                    'product_id' => $product->id,
                    'merchant_id' => $merchantId,
                    'sku_code' => $sku['sku_code'],
                    'sku_specs' => json_encode($sku['attributes'] ?? [], JSON_UNESCAPED_UNICODE),
                    'price' => $sku['price'],
                    'market_price' => $sku['market_price'] ?? $sku['price'],
                    'cost_price' => $sku['cost_price'] ?? 0,
                    'stock' => $sku['stock'],
                    'stock_alarm' => $sku['stock_alarm'] ?? 10,
                    'weight' => $sku['weight'] ?? 0,
                    'image' => $sku['image'] ?? null,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->syncPriceAndStockFromSkus($product->id, $merchantId);
    }

    private function syncPriceAndStockFromSkus(int $productId, int $merchantId): void
    {
        $skus = DB::table('product_skus')
            ->where('product_id', $productId)
            ->where('merchant_id', $merchantId)
            ->where('status', 1)
            ->get();

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

    private function decodeImages(mixed $images): array
    {
        if (is_string($images)) {
            $decoded = json_decode($images, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($images) ? $images : [];
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

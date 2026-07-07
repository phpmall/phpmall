<?php

declare(strict_types=1);

namespace App\Modules\Cart\Services;

use App\Api\User\Responses\Cart\CartItemResponse;
use App\Api\User\Responses\Cart\CartListResponse;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Services\CommonService;

class CartService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly CartRepository $repository,
    ) {}

    public function getRepository(): CartRepository
    {
        return $this->repository;
    }

    /**
     * 获取购物车列表（按商家分组排序）
     */
    public function getCartList(int $userId, int $page = 1, int $perPage = 20): CartListResponse
    {
        $page = max(1, $page);
        $perPage = min(100, max(1, $perPage));

        $result = $this->repository->page(['user_id' => $userId], $page, $perPage, 'merchant_id', 'asc');
        $items = $result['data'] ?? [];

        $cartItemResponses = [];
        $totalCount = 0;
        $selectedCount = 0;
        $totalAmount = 0;
        $invalidCount = 0;

        foreach ($items as $cartItem) {
            $cartItem = (array) $cartItem;
            $responseItem = $this->buildCartItemResponse($cartItem);
            $cartItemResponses[] = $responseItem;

            $totalCount += $responseItem->getQuantity();

            if ($responseItem->getIsSelected() === 1 && $responseItem->getIsValid() === 1) {
                $selectedCount += $responseItem->getQuantity();
                $totalAmount += $responseItem->getTotalPrice();
            }

            if ($responseItem->getIsValid() === 0) {
                $invalidCount += 1;
            }
        }

        $listResponse = new CartListResponse;
        $listResponse->setItems($cartItemResponses);
        $listResponse->setTotalCount($totalCount);
        $listResponse->setSelectedCount($selectedCount);
        $listResponse->setTotalAmount($totalAmount);
        $listResponse->setInvalidCount($invalidCount);

        return $listResponse;
    }

    /**
     * 添加商品到购物车
     */
    public function addItem(int $userId, int $skuId, int $quantity): array
    {
        $sku = $this->resolveValidSku($skuId);
        $product = $this->resolveValidProduct((int) $sku['product_id']);

        $this->validateStock($sku, $quantity);

        $existing = (array) $this->repository->find(['user_id' => $userId, 'sku_id' => $skuId]);

        if (! empty($existing)) {
            $newQuantity = (int) $existing['quantity'] + $quantity;
            $this->validateStock($sku, $newQuantity);
            $this->repository->updateById(['quantity' => $newQuantity], $existing['id']);
            $cartId = (int) $existing['id'];
        } else {
            $cartId = (int) $this->repository->save([
                'user_id' => $userId,
                'merchant_id' => $sku['merchant_id'],
                'product_id' => $sku['product_id'],
                'sku_id' => $skuId,
                'quantity' => $quantity,
                'is_selected' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->getCartItem($userId, $cartId);
    }

    /**
     * 批量添加商品到购物车
     *
     * @param  array<int, array{sku_id: int, quantity: int}>  $items
     */
    public function batchAddItems(int $userId, array $items): array
    {
        $created = [];

        DB::transaction(function () use ($userId, $items, &$created): void {
            foreach ($items as $item) {
                $created[] = $this->addItem($userId, (int) $item['sku_id'], (int) $item['quantity']);
            }
        });

        return $created;
    }

    /**
     * 更新购物车商品
     */
    public function updateItem(int $userId, int $cartId, int $quantity, ?int $isSelected = null): array
    {
        $cartItem = (array) $this->repository->findById($cartId);

        if (empty($cartItem) || (int) $cartItem['user_id'] !== $userId) {
            throw new BusinessException('购物车商品不存在');
        }

        $sku = $this->resolveValidSku((int) $cartItem['sku_id']);
        $this->validateStock($sku, $quantity);

        $update = ['quantity' => $quantity];
        if ($isSelected !== null) {
            $update['is_selected'] = $isSelected;
        }

        $this->repository->updateById($update, $cartId);

        return $this->getCartItem($userId, $cartId);
    }

    /**
     * 删除购物车商品
     */
    public function deleteItem(int $userId, int $cartId): bool
    {
        $cartItem = (array) $this->repository->findById($cartId);

        if (empty($cartItem) || (int) $cartItem['user_id'] !== $userId) {
            return false;
        }

        return $this->repository->deleteById($cartId);
    }

    /**
     * 清空购物车
     */
    public function clear(int $userId): bool
    {
        return $this->repository->delete(['user_id' => $userId]);
    }

    /**
     * 获取单个购物车项详情
     */
    public function getCartItem(int $userId, int $cartId): array
    {
        $cartItem = (array) $this->repository->findById($cartId);

        if (empty($cartItem) || (int) $cartItem['user_id'] !== $userId) {
            throw new BusinessException('购物车商品不存在');
        }

        $responseItem = $this->buildCartItemResponse($cartItem);

        return $this->serializeCartItem($responseItem);
    }

    /**
     * 解析并校验有效 SKU
     *
     * @return array<string, mixed>
     */
    private function resolveValidSku(int $skuId): array
    {
        $sku = (array) DB::table('product_skus')->where('id', $skuId)->first();

        if (empty($sku)) {
            throw new BusinessException('商品规格不存在');
        }

        if ((int) $sku['status'] !== 1) {
            throw new BusinessException('商品规格已下架');
        }

        return $sku;
    }

    /**
     * 解析并校验有效商品
     *
     * @return array<string, mixed>
     */
    private function resolveValidProduct(int $productId): array
    {
        $product = Product::where('id', $productId)->first();

        if ($product === null) {
            throw new BusinessException('商品不存在');
        }

        if ((int) $product->status !== 1 || (int) $product->audit_status !== 1) {
            throw new BusinessException('商品已下架或未通过审核');
        }

        return $product->toArray();
    }

    /**
     * 校验库存
     *
     * @param  array<string, mixed>  $sku
     */
    private function validateStock(array $sku, int $quantity): void
    {
        if ($quantity > (int) $sku['stock']) {
            throw new BusinessException('商品库存不足');
        }
    }

    /**
     * 构建购物车项响应 DTO
     *
     * @param  array<string, mixed>  $cartItem
     */
    private function buildCartItemResponse(array $cartItem): CartItemResponse
    {
        $sku = (array) DB::table('product_skus')->where('id', $cartItem['sku_id'])->first();
        $product = Product::withTrashed()->where('id', $cartItem['product_id'])->first();
        $merchant = Merchant::where('id', $cartItem['merchant_id'])->first();

        $isValid = true;
        $invalidReason = null;

        if (empty($sku) || (int) $sku['status'] !== 1) {
            $isValid = false;
            $invalidReason = '商品规格已下架';
        } elseif ($product === null || (int) $product->status !== 1 || (int) $product->audit_status !== 1 || $product->deleted_at !== null) {
            $isValid = false;
            $invalidReason = '商品已下架或未通过审核';
        } elseif ((int) $cartItem['quantity'] > (int) $sku['stock']) {
            $isValid = false;
            $invalidReason = '商品库存不足';
        }

        $quantity = (int) $cartItem['quantity'];
        $price = (int) ($sku['price'] ?? 0);

        $response = new CartItemResponse;
        $response->setId((int) $cartItem['id']);
        $response->setMerchantId((int) $cartItem['merchant_id']);
        $response->setMerchantName($merchant?->name ?? '未知商家');
        $response->setSkuId((int) $cartItem['sku_id']);
        $response->setProductId((int) $cartItem['product_id']);
        $response->setProductName($product?->title ?? '未知商品');
        $response->setSkuName($this->formatSkuSpecs($sku['sku_specs'] ?? null));
        $response->setImage($sku['image'] ?? $product?->main_image ?? null);
        $response->setPrice($price);
        $response->setQuantity($quantity);
        $response->setTotalPrice($price * $quantity);
        $response->setStock((int) ($sku['stock'] ?? 0));
        $response->setIsSelected((int) $cartItem['is_selected']);
        $response->setIsValid($isValid ? 1 : 0);
        $response->setInvalidReason($invalidReason);
        $response->setCreatedAt($cartItem['created_at'] ?? now()->toDateTimeString());

        return $response;
    }

    /**
     * 格式化 SKU 规格为字符串
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

    /**
     * 序列化购物车项为数组
     */
    private function serializeCartItem(CartItemResponse $item): array
    {
        return [
            'id' => $item->getId(),
            'merchantId' => $item->getMerchantId(),
            'merchantName' => $item->getMerchantName(),
            'skuId' => $item->getSkuId(),
            'productId' => $item->getProductId(),
            'productName' => $item->getProductName(),
            'skuName' => $item->getSkuName(),
            'image' => $item->getImage(),
            'price' => $item->getPrice(),
            'quantity' => $item->getQuantity(),
            'totalPrice' => $item->getTotalPrice(),
            'stock' => $item->getStock(),
            'isSelected' => $item->getIsSelected(),
            'isValid' => $item->getIsValid(),
            'invalidReason' => $item->getInvalidReason(),
            'createdAt' => $item->getCreatedAt(),
        ];
    }
}

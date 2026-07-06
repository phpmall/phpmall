<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\ProductSku\ProductSkuBatchUpdateRequest;
use App\Api\Seller\Requests\ProductSku\ProductSkuIndexRequest;
use App\Api\Seller\Requests\ProductSku\ProductSkuStoreRequest;
use App\Api\Seller\Requests\ProductSku\ProductSkuUpdateRequest;
use App\Api\Seller\Responses\ProductSku\ProductSkuListResponse;
use App\Api\Seller\Responses\ProductSku\ProductSkuResponse;
use App\Modules\Product\Services\ProductSkuService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductSkuController extends BaseController
{
    #[OA\Get(path: '/product-skus', summary: '获取商品SKU列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'product_id', description: '商品ID', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuListResponse::class))]
    public function index(ProductSkuIndexRequest $request): JsonResponse
    {
        $result = app(ProductSkuService::class)->paginateByMerchantId(
            $this->getMerchantId(),
            $request->validated()
        );

        $response = new ProductSkuListResponse;
        $response->setItems(array_map(
            fn (ProductSkuResponse $item): array => $item->toArray(),
            $result['items']
        ));
        $response->setPagination($result['pagination']);

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/product-skus', summary: '创建商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuResponse::class))]
    public function store(ProductSkuStoreRequest $request): JsonResponse
    {
        $sku = app(ProductSkuService::class)->createForMerchant($this->getMerchantId(), $request->validated());

        return $this->success(app(ProductSkuService::class)->toResponse($sku->toArray())->toArray());
    }

    #[OA\Put(path: '/product-skus/{id}', summary: '更新商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: 'SKU ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuResponse::class))]
    public function update(ProductSkuUpdateRequest $request, int $id): JsonResponse
    {
        $sku = app(ProductSkuService::class)->updateForMerchant($id, $this->getMerchantId(), $request->validated());

        return $this->success(app(ProductSkuService::class)->toResponse($sku->toArray())->toArray());
    }

    #[OA\Delete(path: '/product-skus/{id}', summary: '删除商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: 'SKU ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        $deleted = app(ProductSkuService::class)->deleteForMerchant($id, $this->getMerchantId());

        if (! $deleted) {
            return $this->error('SKU不存在', 404);
        }

        return $this->success(['message' => '删除成功']);
    }

    #[OA\Post(path: '/product-skus/batch', summary: '批量更新商品SKU', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuBatchUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchUpdate(ProductSkuBatchUpdateRequest $request): JsonResponse
    {
        app(ProductSkuService::class)->batchUpdate($this->getMerchantId(), $request->input('items'));

        return $this->success(['message' => '批量更新成功']);
    }

    private function getMerchantId(): int
    {
        $payloadMerchantId = request()->attributes->get('jwt_merchant_id');
        if ($payloadMerchantId !== null) {
            return (int) $payloadMerchantId;
        }

        return $this->queryWrapper()[self::MerchantId];
    }
}

<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Product\ProductBatchDeleteRequest;
use App\Api\Seller\Requests\Product\ProductBatchOffShelfRequest;
use App\Api\Seller\Requests\Product\ProductBatchOnShelfRequest;
use App\Api\Seller\Requests\Product\ProductIndexRequest;
use App\Api\Seller\Requests\Product\ProductStoreRequest;
use App\Api\Seller\Requests\Product\ProductUpdateRequest;
use App\Api\Seller\Responses\Product\ProductListResponse;
use App\Api\Seller\Responses\Product\ProductResponse;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductController extends BaseController
{
    #[OA\Get(path: '/products', summary: '获取商品列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'status', description: '商品状态', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'keyword', description: '搜索关键词', in: 'query', required: false, schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\Parameter(name: 'category_id', description: '分类ID', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductListResponse::class))]
    public function index(ProductIndexRequest $request): JsonResponse
    {
        $result = app(ProductService::class)->paginateByMerchantId(
            $this->getMerchantId(),
            $request->validated()
        );

        $response = new ProductListResponse;
        $response->setItems(array_map(
            fn (ProductResponse $item): array => $item->toArray(),
            $result['items']
        ));
        $response->setPagination($result['pagination']);

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/products', summary: '创建商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function store(ProductStoreRequest $request): JsonResponse
    {
        $product = app(ProductService::class)->createForMerchant($this->getMerchantId(), $request->validated());

        return $this->success(app(ProductService::class)->toResponse($product->toArray())->toArray());
    }

    #[OA\Get(path: '/products/{id}', summary: '获取商品详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function show(int $id): JsonResponse
    {
        $product = app(ProductService::class)->findForMerchant($id, $this->getMerchantId());

        if ($product === null) {
            return $this->error('商品不存在', 404);
        }

        return $this->success(app(ProductService::class)->toResponse($product->toArray())->toArray());
    }

    #[OA\Put(path: '/products/{id}', summary: '更新商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        $product = app(ProductService::class)->updateForMerchant($id, $this->getMerchantId(), $request->validated());

        return $this->success(app(ProductService::class)->toResponse($product->toArray())->toArray());
    }

    #[OA\Delete(path: '/products/{id}', summary: '删除商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        $deleted = app(ProductService::class)->deleteForMerchant($id, $this->getMerchantId());

        if (! $deleted) {
            return $this->error('商品不存在', 404);
        }

        return $this->success(['message' => '删除成功']);
    }

    #[OA\Post(path: '/products/{id}/on-shelf', summary: '商品上架', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function onShelf(int $id): JsonResponse
    {
        $updated = app(ProductService::class)->updateStatus($id, $this->getMerchantId(), 1);

        if (! $updated) {
            return $this->error('商品不存在', 404);
        }

        return $this->success(['message' => '上架成功']);
    }

    #[OA\Post(path: '/products/{id}/off-shelf', summary: '商品下架', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function offShelf(int $id): JsonResponse
    {
        $updated = app(ProductService::class)->updateStatus($id, $this->getMerchantId(), 0);

        if (! $updated) {
            return $this->error('商品不存在', 404);
        }

        return $this->success(['message' => '下架成功']);
    }

    #[OA\Post(path: '/products/batch/on-shelf', summary: '批量上架商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBatchOnShelfRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchOnShelf(ProductBatchOnShelfRequest $request): JsonResponse
    {
        app(ProductService::class)->batchUpdateStatus($request->input('ids'), $this->getMerchantId(), 1);

        return $this->success(['message' => '批量上架成功']);
    }

    #[OA\Post(path: '/products/batch/off-shelf', summary: '批量下架商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBatchOffShelfRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchOffShelf(ProductBatchOffShelfRequest $request): JsonResponse
    {
        app(ProductService::class)->batchUpdateStatus($request->input('ids'), $this->getMerchantId(), 0);

        return $this->success(['message' => '批量下架成功']);
    }

    #[OA\Post(path: '/products/batch/delete', summary: '批量删除商品', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductBatchDeleteRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchDelete(ProductBatchDeleteRequest $request): JsonResponse
    {
        app(ProductService::class)->batchDelete($request->input('ids'), $this->getMerchantId());

        return $this->success(['message' => '批量删除成功']);
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

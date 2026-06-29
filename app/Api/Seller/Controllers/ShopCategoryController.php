<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\ShopCategory\ShopCategoryIndexRequest;
use App\Api\Seller\Requests\ShopCategory\ShopCategoryReorderRequest;
use App\Api\Seller\Requests\ShopCategory\ShopCategoryStoreRequest;
use App\Api\Seller\Requests\ShopCategory\ShopCategoryUpdateRequest;
use App\Api\Seller\Responses\ShopCategory\ShopCategoryListResponse;
use App\Api\Seller\Responses\ShopCategory\ShopCategoryResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShopCategoryController extends BaseController
{
    #[OA\Get(path: '/shop-categories', summary: '获取店铺分类列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCategoryListResponse::class))]
    public function index(ShopCategoryIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/shop-categories', summary: '创建店铺分类', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCategoryStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCategoryResponse::class))]
    public function store(ShopCategoryStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/shop-categories/{id}', summary: '更新店铺分类', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '分类ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCategoryUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCategoryResponse::class))]
    public function update(ShopCategoryUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/shop-categories/{id}', summary: '删除店铺分类', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '分类ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/shop-categories/reorder', summary: '店铺分类排序', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCategoryReorderRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function reorder(ShopCategoryReorderRequest $request): JsonResponse
    {
        return $this->success();
    }
}

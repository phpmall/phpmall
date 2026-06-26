<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Shop\ShopProductsRequest;
use App\Api\Shop\Requests\Shop\ShopReviewsRequest;
use App\Api\Shop\Responses\Shop\ShopProductListResponse;
use App\Api\Shop\Responses\Shop\ShopResponse;
use App\Api\Shop\Responses\Shop\ShopReviewListResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShopController extends BaseController
{
    #[OA\Get(path: '/shops/{id}', summary: '店铺详情', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/shops/{id}/products', summary: '店铺商品列表', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopProductListResponse::class))]
    public function products(int $id, ShopProductsRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/shops/{id}/reviews', summary: '店铺评价列表', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopReviewListResponse::class))]
    public function reviews(int $id, ShopReviewsRequest $request): JsonResponse
    {
        return $this->success();
    }
}

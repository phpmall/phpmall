<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\ShopDecoration\ShopDecorationIndexRequest;
use App\Api\Seller\Requests\ShopDecoration\ShopDecorationUpdateRequest;
use App\Api\Seller\Responses\ShopDecoration\ShopDecorationPreviewResponse;
use App\Api\Seller\Responses\ShopDecoration\ShopDecorationResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShopDecorationController extends BaseController
{
    #[OA\Get(path: '/shop-decoration', summary: '获取店铺装修信息', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopDecorationResponse::class))]
    public function index(ShopDecorationIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/shop-decoration', summary: '更新店铺装修', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopDecorationUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopDecorationResponse::class))]
    public function update(ShopDecorationUpdateRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/shop-decoration/preview', summary: '预览店铺装修', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopDecorationPreviewResponse::class))]
    public function preview(): JsonResponse
    {
        return $this->success();
    }
}

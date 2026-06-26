<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Shop\ShopUpdateRequest;
use App\Api\Seller\Responses\Shop\ShopResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ShopController extends BaseController
{
    #[OA\Get(path: '/shop', summary: '获取店铺信息', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/shop', summary: '更新店铺信息', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopResponse::class))]
    public function update(ShopUpdateRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/shop/close', summary: '关闭店铺', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function close(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/shop/open', summary: '开启店铺', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function open(): JsonResponse
    {
        return $this->success();
    }
}

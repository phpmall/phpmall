<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ShopReviewController extends BaseController
{
    #[OA\Get(path: '/shop-reviews', summary: '获取店铺评价列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/shop-reviews/{id}/reply', summary: '回复店铺评价', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '评价ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function reply(Request $request, int $id): JsonResponse
    {
        return $this->success();
    }
}

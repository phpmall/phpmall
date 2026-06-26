<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Responses\SubOrder\SubOrderListResponse;
use App\Api\Seller\Responses\SubOrder\SubOrderResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SubOrderController extends BaseController
{
    #[OA\Get(path: '/sub-orders', summary: '获取子订单列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SubOrderListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/sub-orders/{id}', summary: '获取子订单详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '子订单ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SubOrderResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}

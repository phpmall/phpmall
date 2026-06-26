<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PurchaseOrderController extends BaseController
{
    #[OA\Get(path: '/purchase-orders', summary: '采购订单列表', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/purchase-orders/{id}', summary: '采购订单详情', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/purchase-orders/{id}/ship', security: [['bearerAuth' => []]], summary: '采购订单发货', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function ship(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/purchase-orders/{id}/confirm', security: [['bearerAuth' => []]], summary: '采购订单确认', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function confirm(): JsonResponse
    {
        return $this->success();
    }
}

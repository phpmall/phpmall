<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class InventoryReservationController extends BaseController
{
    #[OA\Get(path: '/inventory-reservations', summary: '获取库存预留列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/inventory-reservations/{id}/release', summary: '释放库存预留', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '预留ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function release(int $id): JsonResponse
    {
        return $this->success();
    }
}

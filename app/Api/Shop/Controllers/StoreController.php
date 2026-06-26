<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class StoreController extends BaseController
{
    #[OA\Get(path: '/stores', summary: '门店列表', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/stores/{id}', summary: '门店详情', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/stores/nearby', summary: '附近门店', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function nearby(int $id): JsonResponse
    {
        return $this->success();
    }
}

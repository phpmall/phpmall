<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class WarehouseController extends BaseController
{
    #[OA\Get(path: '/warehouses', summary: '仓库列表', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/warehouses', security: [['bearerAuth' => []]], summary: '创建仓库', tags: ['供应商中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/warehouses/{id}', summary: '仓库详情', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/warehouses/{id}', security: [['bearerAuth' => []]], summary: '更新仓库', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->success();
    }
}

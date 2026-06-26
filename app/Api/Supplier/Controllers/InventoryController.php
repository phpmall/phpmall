<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class InventoryController extends BaseController
{
    #[OA\Get(path: '/inventory', summary: '库存列表', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/inventory/{id}', security: [['bearerAuth' => []]], summary: '更新库存', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/inventory/batch', security: [['bearerAuth' => []]], summary: '批量更新库存', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchUpdate(Request $request): JsonResponse
    {
        return $this->success();
    }
}

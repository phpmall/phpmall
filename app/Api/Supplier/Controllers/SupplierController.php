<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SupplierController extends BaseController
{
    #[OA\Get(path: '/supplier', summary: '供应商信息', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/supplier', security: [['bearerAuth' => []]], summary: '更新供应商信息', tags: ['供应商中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/supplier/profile', security: [['bearerAuth' => []]], summary: '供应商资料', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function profile(): JsonResponse
    {
        return $this->success();
    }
}

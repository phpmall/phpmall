<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\Supplier\SupplierIndexRequest;
use App\Api\Supplier\Requests\Supplier\UpdateRequest;
use App\Api\Supplier\Responses\Supplier\SupplierProfileResponse;
use App\Api\Supplier\Responses\Supplier\SupplierResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SupplierController extends BaseController
{
    #[OA\Get(path: '/supplier', summary: '供应商列表', security: [['bearerAuth' => []]], tags: ['供应商中心'])]
    #[OA\Parameter(name: 'status', description: '供应商状态', in: 'query', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierResponse::class))]
    public function index(SupplierIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/supplier', security: [['bearerAuth' => []]], summary: '更新供应商信息', tags: ['供应商中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierResponse::class))]
    public function update(UpdateRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/supplier/profile', security: [['bearerAuth' => []]], summary: '供应商资料', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierProfileResponse::class))]
    public function profile(): JsonResponse
    {
        return $this->success();
    }
}

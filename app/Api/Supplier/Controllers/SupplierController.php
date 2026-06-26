<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\Supplier\UpdateRequest;
use App\Api\Supplier\Responses\Supplier\SupplierProfileResponse;
use App\Api\Supplier\Responses\Supplier\SupplierResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SupplierController extends BaseController
{
    #[OA\Get(path: '/supplier', summary: '供应商信息', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierResponse::class))]
    public function index(Request $request): JsonResponse
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

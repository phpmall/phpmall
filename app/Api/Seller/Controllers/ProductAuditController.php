<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Responses\ProductAudit\ProductAuditListResponse;
use App\Api\Seller\Responses\ProductAudit\ProductAuditResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductAuditController extends BaseController
{
    #[OA\Get(path: '/product-audits', summary: '获取商品审核列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductAuditListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/product-audits/{id}', summary: '获取商品审核详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '审核ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductAuditResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}

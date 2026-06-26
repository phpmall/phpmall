<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Responses\Category\CategoryListResponse;
use App\Api\Seller\Responses\Category\CategoryTreeResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CategoryController extends BaseController
{
    #[OA\Get(path: '/categories', summary: '获取分类列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/categories/tree', summary: '获取分类树', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryTreeResponse::class))]
    public function tree(): JsonResponse
    {
        return $this->success();
    }
}

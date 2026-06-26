<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Category\CategoryIndexRequest;
use App\Api\Portal\Responses\Category\CategoryListResponse;
use App\Api\Portal\Responses\Category\CategoryTreeResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CategoryController extends BaseController
{
    #[OA\Get(path: '/categories', summary: '分类列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryListResponse::class))]
    public function index(CategoryIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/categories/tree', summary: '分类树', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryTreeResponse::class))]
    public function tree(): JsonResponse
    {
        return $this->success();
    }
}

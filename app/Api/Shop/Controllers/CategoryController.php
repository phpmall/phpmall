<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Category\CategoryIndexRequest;
use App\Api\Shop\Responses\Category\CategoryListResponse;
use App\Api\Shop\Responses\Category\CategoryTreeResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CategoryController extends BaseController
{
    #[OA\Get(path: '/categories', summary: '分类列表', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryListResponse::class))]
    public function index(CategoryIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/categories/tree', summary: '分类树', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryTreeResponse::class))]
    public function tree(): JsonResponse
    {
        return $this->success();
    }
}

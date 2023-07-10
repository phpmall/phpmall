<?php

declare(strict_types=1);

namespace App\Gateways\Portal\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CategoryController extends BaseController
{
    #[OA\Get(path: '/category', summary: '商品分类', tags: ['商品分类'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['category']);
    }
}

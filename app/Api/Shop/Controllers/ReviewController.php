<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ReviewController extends BaseController
{
    #[OA\Get(path: '/reviews', summary: '评价列表', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }
}

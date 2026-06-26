<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class HelpCenterController extends BaseController
{
    #[OA\Get(path: '/help-center', summary: '帮助中心列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/help-center/{id}', summary: '帮助中心详情', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/help-center/search', summary: '帮助中心搜索', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function search(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/help-center/categories', summary: '帮助中心分类', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function categories(Request $request): JsonResponse
    {
        return $this->success();
    }
}

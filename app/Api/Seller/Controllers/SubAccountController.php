<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SubAccountController extends BaseController
{
    #[OA\Get(path: '/sub-accounts', summary: '获取子账号列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/sub-accounts', summary: '创建子账号', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/sub-accounts/{id}/enable', summary: '启用子账号', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '子账号ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function enable(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/sub-accounts/{id}/disable', summary: '禁用子账号', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '子账号ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function disable(int $id): JsonResponse
    {
        return $this->success();
    }
}

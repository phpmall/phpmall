<?php

declare(strict_types=1);

namespace App\Api\Manager\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends BaseController
{
    #[OA\Get(path: '/admin/user', summary: '用户列表', security: [['bearerAuth' => []]], tags: ['用户管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success('query');
    }

    #[OA\Post(path: '/admin/user/store', summary: '添加新用户', security: [['bearerAuth' => []]], tags: ['用户管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    #[OA\Get(path: '/admin/user/show', summary: '获取详情', security: [['bearerAuth' => []]], tags: ['用户管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    #[OA\Put(path: '/admin/user/update', summary: '更新用户详情', security: [['bearerAuth' => []]], tags: ['用户管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    #[OA\Delete(path: '/admin/user/destroy', summary: '删除用户', security: [['bearerAuth' => []]], tags: ['用户管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

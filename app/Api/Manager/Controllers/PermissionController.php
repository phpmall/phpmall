<?php

declare(strict_types=1);

namespace App\Api\Manager\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PermissionController extends BaseController
{
    #[OA\Get(path: 'admin/permission', summary: '权限列表', tags: ['权限管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }

    /**
     * 权限搜索
     */
    public function queryHandle(Request $request): JsonResponse
    {
        return $this->success('query');
    }

    /**
     * 创建表单
     */
    public function create(): Renderable
    {
        return view('create');
    }

    /**
     * 保存权限
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示权限
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑权限
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新权限
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除权限
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

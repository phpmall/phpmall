<?php

declare(strict_types=1);

namespace App\Bundles\System\Controllers\Manager;

use App\Http\Controllers\Manager\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RoleController extends BaseController
{
    #[OA\Get(path: '/role', summary: '角色列表', tags: ['角色管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }

    /**
     * 角色搜索
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
     * 保存角色
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示角色
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑角色
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新角色
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除角色
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

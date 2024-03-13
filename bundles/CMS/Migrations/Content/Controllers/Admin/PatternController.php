<?php

declare(strict_types=1);

namespace App\Bundles\CMS\Migrations\Content\Controllers\Admin;

use App\Api\Admin\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use function App\Bundles\Content\Controllers\Admin\view;

class PatternController extends BaseController
{
    #[OA\Get(path: '/pattern', summary: '模型列表', security: [['bearerAuth' => []]], tags: ['模型管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 模型搜索
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
     * 保存模型
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示模型
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑模型
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新模型
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除模型
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\CMS\Migrations\Content\Controllers\Admin;

use App\Api\Admin\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use function App\Bundles\Content\Controllers\Admin\view;

class CategoryController extends BaseController
{
    #[OA\Get(path: '/category', summary: '栏目列表', security: [['bearerAuth' => []]], tags: ['栏目管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 栏目搜索
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
     * 保存栏目
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示栏目
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑栏目
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新栏目
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除栏目
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

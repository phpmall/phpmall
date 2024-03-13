<?php

declare(strict_types=1);

namespace App\Bundles\CMS\Migrations\Comment\Controllers\Admin;

use App\Api\Admin\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use function App\Bundles\Comment\Controllers\Admin\view;

class IndexController extends BaseController
{
    #[OA\Get(path: '/comment', summary: '评论列表', security: [['bearerAuth' => []]], tags: ['评论管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 评论搜索
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
     * 保存评论
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示评论
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑评论
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新评论
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除评论
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\Content\Controllers\Admin;

use App\Gateways\Admin\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SegmentController extends BaseController
{
    /**
     * 标签列表
     */
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 标签搜索
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
     * 保存标签
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示标签
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑标签
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新标签
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除标签
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\Content\Controllers\Manager;

use App\Http\Controllers\Manager\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentController extends BaseController
{
    /**
     * 内容列表
     */
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 内容搜索
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
     * 保存内容
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示内容
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑内容
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新内容
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除内容
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

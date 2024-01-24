<?php

declare(strict_types=1);

namespace App\Bundles\Portal\Controllers\Manager;

use App\Api\Manager\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkController extends BaseController
{
    /**
     * 链接列表
     */
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 链接搜索
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
     * 保存链接
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示链接
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑链接
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新链接
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除链接
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

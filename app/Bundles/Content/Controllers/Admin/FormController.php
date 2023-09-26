<?php

declare(strict_types=1);

namespace App\Bundles\Content\Controllers\Admin;

use App\Gateways\Manager\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormController extends BaseController
{
    /**
     * 表单列表
     */
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 表单搜索
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
     * 保存表单
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示表单
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑表单
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新表单
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除表单
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

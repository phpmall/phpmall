<?php

declare(strict_types=1);

namespace App\Bundles\Portal\Controllers\Admin;

use App\Gateways\Admin\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NavController extends BaseController
{
    /**
     * 导航列表
     */
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 导航搜索
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
     * 保存导航
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示导航
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑导航
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新导航
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除导航
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Content\API\Manager;

use App\API\Manager\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdPositionController extends BaseController
{
    /**
     * 广告位列表
     */
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 广告位搜索
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
     * 保存广告位
     */
    public function storeHandle(Request $request): JsonResponse
    {
        return $this->success('store');
    }

    /**
     * 显示广告位
     */
    public function show(): JsonResponse
    {
        return $this->success('show');
    }

    /**
     * 编辑广告位
     */
    public function edit(): Renderable
    {
        return view('edit');
    }

    /**
     * 更新广告位
     */
    public function updateHandle(Request $request): JsonResponse
    {
        return $this->success('update');
    }

    /**
     * 删除广告位
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('destroy');
    }
}

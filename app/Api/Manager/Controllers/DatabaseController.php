<?php

declare(strict_types=1);

namespace App\Api\Manager\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DatabaseController extends BaseController
{
    /**
     * 数据库管理
     */
    public function index(): Renderable
    {
        return view('index');
    }

    /**
     * 数据库搜索
     */
    public function queryHandle(Request $request): JsonResponse
    {
        return $this->success('query');
    }

    /**
     * 数据库备份
     */
    public function backupHandle(Request $request): JsonResponse
    {
        return $this->success('');
    }

    /**
     * 数据库回滚
     */
    public function rollbackHandle(Request $request): JsonResponse
    {
        return $this->success('');
    }

    /**
     * 删除备份
     */
    public function destroyHandle(Request $request): JsonResponse
    {
        return $this->success('');
    }
}

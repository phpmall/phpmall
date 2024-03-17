<?php

declare(strict_types=1);

namespace App\Bundles\System\Controllers\Manager;

use App\API\Manager\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SystemController extends BaseController
{
    /**
     * 关于我们
     */
    public function about(): JsonResponse
    {
        return $this->success('');
    }

    /**
     * 系统日志
     */
    public function log(): JsonResponse
    {
        return $this->success('');
    }

    /**
     * 在线更新
     */
    public function upgrade(): JsonResponse
    {
        return $this->success('');
    }

    /**
     * 在线更新
     */
    public function upgradeHandle(Request $request): JsonResponse
    {
        return $this->success('');
    }
}

<?php

declare(strict_types=1);

namespace Juling\Portal\Controllers\Manager;

use App\API\Manager\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class SettingController extends BaseController
{
    /**
     * 基本参数
     */
    public function basic(): JsonResponse
    {
        return $this->success('');
    }

    /**
     * 公司信息
     */
    public function company(): JsonResponse
    {
        return $this->success('');
    }

    /**
     * 网站信息
     */
    public function site(): JsonResponse
    {
        return $this->success('');
    }

    /**
     * 邮件设置
     */
    public function email(): JsonResponse
    {
        return $this->success('');
    }
}

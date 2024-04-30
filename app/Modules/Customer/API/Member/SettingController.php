<?php

declare(strict_types=1);

namespace App\Modules\Customer\API\Member;

use App\API\Customer\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    /**
     * 修改密码
     */
    public function editPasswordHandle(Request $request): JsonResponse
    {
        return $this->success('data');
    }
}

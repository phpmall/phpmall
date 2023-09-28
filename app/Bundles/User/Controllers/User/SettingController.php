<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers\User;

use App\Gateways\User\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    /**
     * 显示修改密码表单
     */
    public function editPassword(): Renderable
    {
        return view('edit_password');
    }

    /**
     * 修改密码
     */
    public function editPasswordHandle(Request $request): JsonResponse
    {
        return $this->success('data');
    }
}

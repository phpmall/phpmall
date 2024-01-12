<?php

declare(strict_types=1);

namespace App\Portal\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class AuthController extends BaseController
{
    /**
     * 会员登录
     */
    public function login(): Renderable
    {
        return $this->display('auth.login');
    }

    /**
     * 会员注册
     */
    public function signup(): Renderable
    {
        return $this->display('auth.signup');
    }

    /**
     * 忘记密码
     */
    public function forget(): Renderable
    {
        return $this->display('auth.forget');
    }

    /**
     * 重设密码
     */
    public function reset(): Renderable
    {
        return $this->display('auth.reset');
    }
}

<?php

declare (strict_types=1);

namespace app\controller\admin;

use app\support\Controller;
use think\exception\ValidateException;
use think\Request;
use think\Response;

/**
 * Class AuthController
 * @package app\controller\admin
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *  path="admin/auth/login",
     *  tags={"admin"},
     *  summary="管理员登录",
     *  description="管理员登录的详细描述",
     *  @OA\Parameter(in="query", name="username & password", description = "手机号码 && 密码", required = true),
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @param Request $request
     * @return Response
     */
    public function loginHandler(Request $request): Response
    {
        try {
            $this->validate($request->param(), [
                'username|用户名' => 'require|max:16',
                'password|登录密码' => 'require',
                'captcha|验证码' => 'require|captcha',
            ]);
        } catch (ValidateException $exception) {
            return $this->failed($exception->getMessage());
        }

        return $this->succeed('ok');
    }
}

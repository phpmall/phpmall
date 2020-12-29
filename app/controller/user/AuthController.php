<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Request;
use think\Response;

/**
 * Class AuthController
 * @package app\controller\user
 */
class AuthController extends Controller
{
    /**
     * 买家注册
     * @OA\Post(
     *  path="user/auth/register",
     *  tags={"user"},
     *  summary="用户注册",
     *  description="用户注册的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @param Request $request
     * @return Response
     */
    public function registerHandler(Request $request): Response
    {
        $username = $request->get('username');
        $password = $request->get('password');

        return $this->succeed('ok');
    }

    /**
     * @OA\Post(
     *  path="user/auth/login",
     *  tags={"user"},
     *  summary="用户登录",
     *  description="用户登录的详细描述",
     *  @OA\Parameter(name="username", in="query", @OA\Schema(type="string"), required=true, description="用户名"),
     *  @OA\Parameter(name="password", in="query", @OA\Schema(type="string"), required=true, description="登录密码"),
     *  @OA\Response(response="200", description="用户登录的详细描述")
     * )
     * @param Request $request
     * @return Response
     */
    public function loginHandler(Request $request): Response
    {
        $username = $request->get('username');
        $password = $request->get('password');

        return $this->succeed('ok');
    }
}

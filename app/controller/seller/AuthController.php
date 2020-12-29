<?php

declare (strict_types=1);

namespace app\controller\seller;

use app\support\Controller;
use think\Request;
use think\Response;

/**
 * Class AuthController
 * @package app\controller\seller
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *  path="seller/auth/login",
     *  tags={"seller"},
     *  summary="商家登录",
     *  description="商家登录的详细描述",
     *  @OA\Response(response="200", description="An example resource")
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

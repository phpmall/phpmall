<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class AccountController
 * @package app\controller\user
 */
class AccountController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/account",
     *  tags={"user"},
     *  summary="账户资金",
     *  description="账户资金的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

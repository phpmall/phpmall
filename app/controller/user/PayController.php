<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class PayController
 * @package app\controller\user
 */
class PayController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/pay",
     *  tags={"user"},
     *  summary="在线支付",
     *  description="在线支付的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

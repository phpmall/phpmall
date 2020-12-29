<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class OrderController
 * @package app\controller\user
 */
class OrderController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/order",
     *  tags={"user"},
     *  summary="订单",
     *  description="订单的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

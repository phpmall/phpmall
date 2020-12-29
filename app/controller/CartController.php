<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class CartController
 * @package app\controller
 */
class CartController extends Controller
{
    /**
     * @OA\Get(
     *  path="cart",
     *  summary="购物车",
     *  description="购物车的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

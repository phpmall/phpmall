<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class GoodsController
 * @package app\controller
 */
class GoodsController extends Controller
{
    /**
     * @OA\Get(
     *  path="goods",
     *  summary="商品",
     *  description="商品的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class ShopController
 * @package app\controller
 */
class ShopController extends Controller
{
    /**
     * @OA\Get(
     *  path="shop",
     *  summary="商家店铺",
     *  description="商家店铺的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

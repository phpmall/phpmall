<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class TradeController
 * @package app\controller
 */
class TradeController extends Controller
{
    /**
     * @OA\Get(
     *  path="trade",
     *  summary="交易处理",
     *  description="交易处理的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

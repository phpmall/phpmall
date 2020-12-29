<?php

declare (strict_types=1);

namespace app\controller\seller;

use app\support\Controller;
use think\Request;
use think\Response;

/**
 * Class IndexController
 * @package app\controller\seller
 */
class IndexController extends Controller
{
    /**
     * @OA\Get(
     *  path="seller",
     *  tags={"seller"},
     *  summary="商家管理仪表盘",
     *  description="商家管理仪表盘的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->succeed('hello seller');
    }
}

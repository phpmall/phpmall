<?php

declare (strict_types=1);

namespace app\controller\admin;

use app\support\Controller;
use think\Request;
use think\Response;

/**
 * Class IndexController
 * @package app\controller\admin
 */
class IndexController extends Controller
{
    /**
     * @OA\Get(
     *  path="admin",
     *  tags={"admin"},
     *  summary="平台管理仪表盘",
     *  description="平台管理仪表盘的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->succeed('hello admin');
    }
}

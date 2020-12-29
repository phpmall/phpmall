<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class IndexController
 * @package app\controller\user
 */
class IndexController extends Controller
{
    /**
     * @OA\Get(
     *  path="user",
     *  tags={"user"},
     *  summary="用户仪表盘",
     *  description="用户仪表盘的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

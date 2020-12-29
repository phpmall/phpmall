<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class CollectController
 * @package app\controller\user
 */
class CollectController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/collect",
     *  tags={"user"},
     *  summary="个人收藏",
     *  description="个人收藏的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

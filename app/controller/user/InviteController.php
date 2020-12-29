<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class InviteController
 * @package app\controller\user
 */
class InviteController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/invite",
     *  tags={"user"},
     *  summary="邀请",
     *  description="邀请的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

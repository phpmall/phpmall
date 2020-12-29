<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class ProfileController
 * @package app\controller\user
 */
class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/profile",
     *  tags={"user"},
     *  summary="个人资料",
     *  description="个人资料的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

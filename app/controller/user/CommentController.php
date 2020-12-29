<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class CommentController
 * @package app\controller\user
 */
class CommentController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/comment",
     *  tags={"user"},
     *  summary="评论",
     *  description="评论的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

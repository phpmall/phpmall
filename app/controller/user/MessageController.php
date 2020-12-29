<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class MessageController
 * @package app\controller\user
 */
class MessageController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/message",
     *  tags={"user"},
     *  summary="消息",
     *  description="消息的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

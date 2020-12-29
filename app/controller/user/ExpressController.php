<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class ExpressController
 * @package app\controller\user
 */
class ExpressController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/express",
     *  tags={"user"},
     *  summary="快递",
     *  description="快递的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class PositionController
 * @package app\controller
 */
class PositionController extends Controller
{
    /**
     * @OA\Get(
     *  path="position",
     *  summary="位置服务",
     *  description="位置服务的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

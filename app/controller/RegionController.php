<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class RegionController
 * @package app\controller
 */
class RegionController extends Controller
{
    /**
     * @OA\Get(
     *  path="region",
     *  summary="地区",
     *  description="地区的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

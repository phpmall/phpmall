<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class BrandController
 * @package app\controller
 */
class BrandController extends Controller
{
    /**
     * @OA\Get(
     *  path="brand",
     *  summary="品牌",
     *  description="品牌的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

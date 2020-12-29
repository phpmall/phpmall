<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class CatalogController
 * @package app\controller
 */
class CatalogController extends Controller
{
    /**
     * @OA\Get(
     *  path="catalog",
     *  summary="类目",
     *  description="类目的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

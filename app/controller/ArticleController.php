<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class ArticleController
 * @package app\controller
 */
class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *  path="article",
     *  summary="文章",
     *  description="文章的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

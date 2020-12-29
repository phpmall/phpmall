<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Response;

/**
 * Class HelpController
 * @package app\controller
 */
class HelpController extends Controller
{
    /**
     * @OA\Get(
     *  path="help",
     *  summary="帮助中心",
     *  description="帮助中心的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

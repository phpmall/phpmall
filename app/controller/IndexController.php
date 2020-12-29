<?php

declare (strict_types=1);

namespace app\controller;

use app\support\Controller;
use think\Request;
use think\Response;

/**
 * @OA\Info(title="PHPMALL API", version="1.0", contact={"email": "support@baidu.com"})
 * Class IndexController
 * @package app\controller
 */
class IndexController extends Controller
{
    /**
     * @OA\Get(
     *  path="/",
     *  summary="商城首页",
     *  description="商城首页的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // $data = $this->app->lbs->address2location('上海市中山北路3553号');
        $id = (string)$request->get('id');

        $data = $this->app->lbs->district($id);

        return $this->succeed($data);
    }
}

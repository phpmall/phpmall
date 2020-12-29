<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Request;
use think\Response;

/**
 * Class OAuthController
 * @package app\controller\user
 */
class OAuthController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/oauth",
     *  tags={"user"},
     *  summary="社会化社交登录",
     *  description="社会化社交登录的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $OAuthType = $request->get('type');

        return redirect('');
    }
}

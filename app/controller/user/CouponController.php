<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class CouponController
 * @package app\controller\user
 */
class CouponController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/coupon",
     *  tags={"user"},
     *  summary="优惠券",
     *  description="优惠券的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

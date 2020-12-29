<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class AddressController
 * @package app\controller\user
 */
class AddressController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/address",
     *  tags={"user"},
     *  summary="收货地址",
     *  description="收货地址的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}

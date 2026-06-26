<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CouponController extends BaseController
{
    #[OA\Get(path: '/coupons', summary: '优惠券列表', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }
}

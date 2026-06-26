<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Coupon\CouponIndexRequest;
use App\Api\Shop\Responses\Coupon\CouponListResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CouponController extends BaseController
{
    #[OA\Get(path: '/coupons', summary: '优惠券列表', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CouponListResponse::class))]
    public function index(CouponIndexRequest $request): JsonResponse
    {
        return $this->success();
    }
}

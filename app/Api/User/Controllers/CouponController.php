<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CouponController extends BaseController
{
    #[OA\Get(path: '/coupons', summary: 'Coupon Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/coupons/receive', security: [['bearerAuth' => []]], summary: 'Coupon Controller receive', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function receive(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/coupons/my', security: [['bearerAuth' => []]], summary: 'Coupon Controller my Coupons', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function myCoupons(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/coupons/{id}/use', security: [['bearerAuth' => []]], summary: 'Coupon Controller use', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function use(Request $request): JsonResponse
    {
        return $this->success();
    }
}

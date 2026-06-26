<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Coupon\CouponIndexRequest;
use App\Api\User\Requests\Coupon\CouponReceiveRequest;
use App\Api\User\Requests\Coupon\CouponUseRequest;
use App\Api\User\Responses\Coupon\CouponListResponse;
use App\Api\User\Responses\Coupon\MyCouponListResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CouponController extends BaseController
{
    #[OA\Get(path: '/coupons', security: [['bearerAuth' => []]], summary: 'Coupon Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CouponListResponse::class))]
    public function index(CouponIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/coupons/receive', security: [['bearerAuth' => []]], summary: 'Coupon Controller receive', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CouponReceiveRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MyCouponListResponse::class))]
    public function receive(CouponReceiveRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/coupons/my', security: [['bearerAuth' => []]], summary: 'Coupon Controller my Coupons', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MyCouponListResponse::class))]
    public function myCoupons(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/coupons/{id}/use', security: [['bearerAuth' => []]], summary: 'Coupon Controller use', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CouponUseRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function use(CouponUseRequest $request): JsonResponse
    {
        return $this->success();
    }
}

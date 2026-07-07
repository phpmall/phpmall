<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Coupon\CouponIndexRequest;
use App\Api\User\Requests\Coupon\CouponReceiveRequest;
use App\Api\User\Requests\Coupon\CouponUseRequest;
use App\Api\User\Responses\Coupon\CouponListResponse;
use App\Api\User\Responses\Coupon\MyCouponListResponse;
use App\Api\User\Responses\Coupon\MyCouponResponse;
use App\Modules\Coupon\Services\CouponService;
use App\Modules\User\Models\User;
use App\Modules\User\Services\UserCouponService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;

class CouponController extends BaseController
{
    public function __construct(
        private readonly CouponService $couponService,
        private readonly UserCouponService $userCouponService,
    ) {
        parent::__construct();
    }

    #[OA\Get(path: '/coupons', security: [['bearerAuth' => []]], summary: 'Coupon Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'status', in: 'query', description: '优惠券状态', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CouponListResponse::class))]
    public function index(CouponIndexRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        $result = $this->couponService->getAvailableCoupons(
            $user->id,
            $request->validated()
        );

        $response = new CouponListResponse;
        $response->setItems(array_map(
            fn (object $item): array => $item->toArray(),
            $result['items']
        ));
        $response->setPagination($result['pagination']);

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/coupons/receive', security: [['bearerAuth' => []]], summary: 'Coupon Controller receive', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CouponReceiveRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MyCouponResponse::class))]
    public function receive(CouponReceiveRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $userCoupon = $this->userCouponService->receive(
                $user->id,
                (int) $request->input(CouponReceiveRequest::getCouponId)
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success($this->userCouponService->toMyCouponResponse($userCoupon->toArray())->toArray());
    }

    #[OA\Get(path: '/coupons/my', security: [['bearerAuth' => []]], summary: 'Coupon Controller my Coupons', tags: ['会员中心'])]
    #[OA\Parameter(name: 'status', in: 'query', description: '优惠券状态', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MyCouponListResponse::class))]
    public function myCoupons(CouponIndexRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        $result = $this->userCouponService->getMyCoupons(
            $user->id,
            $request->validated()
        );

        $response = new MyCouponListResponse;
        $response->setItems(array_map(
            fn (object $item): array => $item->toArray(),
            $result['items']
        ));
        $response->setPagination($result['pagination']);

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/coupons/{id}/use', security: [['bearerAuth' => []]], summary: 'Coupon Controller use', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CouponUseRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function use(CouponUseRequest $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $this->userCouponService->useCoupon(
                $user->id,
                $id,
                (int) $request->input(CouponUseRequest::getOrderAmount)
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success(['message' => '使用成功']);
    }

    private function resolveUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401, '未登录');
        }

        return $user;
    }
}

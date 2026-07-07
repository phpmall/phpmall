<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Coupon\CouponIndexRequest;
use App\Api\Seller\Requests\Coupon\CouponStoreRequest;
use App\Api\Seller\Requests\Coupon\CouponUpdateRequest;
use App\Api\Seller\Responses\Coupon\CouponListResponse;
use App\Api\Seller\Responses\Coupon\CouponResponse;
use App\Api\Seller\Responses\Coupon\CouponStatsResponse;
use App\Modules\Coupon\Services\CouponService;
use Illuminate\Http\JsonResponse;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;

class CouponController extends BaseController
{
    public function __construct(
        private readonly CouponService $couponService,
    ) {
        parent::__construct();
    }

    #[OA\Get(path: '/coupons', summary: '获取优惠券列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'status', description: '状态', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CouponListResponse::class))]
    public function index(CouponIndexRequest $request): JsonResponse
    {
        $result = $this->couponService->paginateByMerchantId(
            $this->getMerchantId(),
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

    #[OA\Post(path: '/coupons', summary: '创建优惠券', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CouponStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CouponResponse::class))]
    public function store(CouponStoreRequest $request): JsonResponse
    {
        $coupon = $this->couponService->createForMerchant(
            $this->getMerchantId(),
            $request->validated()
        );

        return $this->success($this->couponService->toResponse($coupon->toArray())->toArray());
    }

    #[OA\Get(path: '/coupons/{id}', summary: '获取优惠券详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '优惠券ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CouponResponse::class))]
    public function show(int $id): JsonResponse
    {
        $coupon = $this->couponService->findForMerchant($id, $this->getMerchantId());

        if ($coupon === null) {
            return $this->error('优惠券不存在', 404);
        }

        return $this->success($this->couponService->toResponse($coupon->toArray())->toArray());
    }

    #[OA\Put(path: '/coupons/{id}', summary: '更新优惠券', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '优惠券ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CouponUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CouponResponse::class))]
    public function update(CouponUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $coupon = $this->couponService->updateForMerchant(
                $id,
                $this->getMerchantId(),
                $request->validated()
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), 404);
        }

        return $this->success($this->couponService->toResponse($coupon->toArray())->toArray());
    }

    #[OA\Delete(path: '/coupons/{id}', summary: '删除优惠券', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '优惠券ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->couponService->deleteForMerchant($id, $this->getMerchantId());

        if (! $deleted) {
            return $this->error('优惠券不存在', 404);
        }

        return $this->success(['message' => '删除成功']);
    }

    #[OA\Post(path: '/coupons/{id}/enable', summary: '启用优惠券', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '优惠券ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function enable(int $id): JsonResponse
    {
        $updated = $this->couponService->updateStatus($id, $this->getMerchantId(), 1);

        if (! $updated) {
            return $this->error('优惠券不存在', 404);
        }

        return $this->success(['message' => '启用成功']);
    }

    #[OA\Post(path: '/coupons/{id}/disable', summary: '禁用优惠券', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '优惠券ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function disable(int $id): JsonResponse
    {
        $updated = $this->couponService->updateStatus($id, $this->getMerchantId(), 0);

        if (! $updated) {
            return $this->error('优惠券不存在', 404);
        }

        return $this->success(['message' => '禁用成功']);
    }

    #[OA\Get(path: '/coupons/{id}/stats', summary: '获取优惠券统计', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '优惠券ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CouponStatsResponse::class))]
    public function stats(int $id): JsonResponse
    {
        try {
            $response = $this->couponService->getStats($id, $this->getMerchantId());
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), 404);
        }

        return $this->success($response->toArray());
    }

    private function getMerchantId(): int
    {
        $payloadMerchantId = request()->attributes->get('jwt_merchant_id');
        if ($payloadMerchantId !== null) {
            return (int) $payloadMerchantId;
        }

        return $this->queryWrapper()[self::MerchantId];
    }
}

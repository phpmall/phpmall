<?php

declare(strict_types=1);

namespace App\Modules\User\Services;

use App\Api\User\Responses\Coupon\MyCouponResponse;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\User\Models\UserCoupon;
use App\Modules\User\Repositories\UserCouponRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Services\CommonService;

class UserCouponService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserCouponRepository $repository,
    ) {}

    public function getRepository(): UserCouponRepository
    {
        return $this->repository;
    }

    /**
     * 领取优惠券
     */
    public function receive(int $userId, int $couponId): UserCoupon
    {
        return DB::transaction(function () use ($userId, $couponId): UserCoupon {
            $coupon = Coupon::where('id', $couponId)
                ->where('status', 1)
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->where('remaining_quantity', '>', 0)
                ->first();

            if ($coupon === null) {
                throw new BusinessException('优惠券不存在或已失效');
            }

            $receivedCount = UserCoupon::where('user_id', $userId)
                ->where('coupon_id', $couponId)
                ->count();

            if ($receivedCount >= $coupon->limit_per_user) {
                throw new BusinessException('已达到领取上限');
            }

            Coupon::where('id', $couponId)->decrement('remaining_quantity');

            return UserCoupon::create([
                'user_id' => $userId,
                'coupon_id' => $couponId,
                'status' => 0,
                'claim_time' => now(),
                'expire_time' => $coupon->end_time,
            ]);
        });
    }

    /**
     * 获取我的优惠券列表
     */
    public function getMyCoupons(int $userId, array $params = []): array
    {
        $page = (int) ($params['page'] ?? 1);
        $perPage = (int) ($params['per_page'] ?? 20);

        $builder = UserCoupon::where('user_id', $userId)
            ->with('coupon')
            ->orderByDesc('created_at');

        if (isset($params['status']) && $params['status'] !== null && $params['status'] !== '') {
            $builder->where('status', (int) $params['status']);
        }

        $result = $builder->paginate($perPage, ['*'], 'page', $page);

        $items = [];
        foreach ($result->items() as $userCoupon) {
            $items[] = $this->toMyCouponResponse($userCoupon->toArray());
        }

        return [
            'items' => $items,
            'pagination' => $this->buildPagination($result),
        ];
    }

    /**
     * 使用优惠券
     */
    public function useCoupon(int $userId, int $userCouponId, int $orderAmount): bool
    {
        return DB::transaction(function () use ($userId, $userCouponId, $orderAmount): bool {
            $userCoupon = UserCoupon::where('id', $userCouponId)
                ->where('user_id', $userId)
                ->first();

            if ($userCoupon === null) {
                throw new BusinessException('优惠券不存在');
            }

            if ($userCoupon->status !== 0) {
                throw new BusinessException('优惠券状态不可用');
            }

            if ($userCoupon->expire_time < now()) {
                throw new BusinessException('优惠券已过期');
            }

            $coupon = Coupon::find($userCoupon->coupon_id);
            if ($coupon === null) {
                throw new BusinessException('优惠券不存在');
            }

            if ($orderAmount < $coupon->threshold_amount) {
                throw new BusinessException('订单金额未达到优惠券使用门槛');
            }

            $userCoupon->update([
                'status' => 1,
                'used_at' => now(),
            ]);

            return true;
        });
    }

    /**
     * 将用户优惠券数据转换为响应 DTO
     */
    public function toMyCouponResponse(array $userCoupon): MyCouponResponse
    {
        $coupon = $userCoupon['coupon'] ?? [];
        $status = (int) $userCoupon['status'];
        if ($status === 0 && isset($userCoupon['expire_time']) && $userCoupon['expire_time'] < now()->toDateTimeString()) {
            $status = 2;
        }

        return MyCouponResponse::from([
            'id' => (int) $userCoupon['id'],
            'coupon_id' => (int) $userCoupon['coupon_id'],
            'name' => $coupon['name'] ?? '',
            'type' => $this->typeToString((int) ($coupon['type'] ?? 1)),
            'amount' => $this->resolveAmount($coupon),
            'min_order_amount' => (int) ($coupon['threshold_amount'] ?? 0),
            'status' => $status,
            'start_time' => $coupon['start_time'] ?? '',
            'end_time' => $coupon['end_time'] ?? '',
            'used_at' => $userCoupon['used_at'] ?? null,
            'order_id' => ! empty($userCoupon['used_order_id']) ? (int) $userCoupon['used_order_id'] : null,
        ]);
    }

    private function resolveAmount(array $coupon): int
    {
        $type = (int) ($coupon['type'] ?? 1);

        if ($type === 2 && ! empty($coupon['discount_rate'])) {
            return (int) round((float) $coupon['discount_rate'] * 1000);
        }

        return (int) ($coupon['discount_amount'] ?? 0);
    }

    private function typeToString(int $type): string
    {
        return match ($type) {
            1 => 'full_reduction',
            2 => 'discount',
            3 => 'no_threshold',
            4 => 'exchange',
            default => 'unknown',
        };
    }

    private function buildPagination(mixed $result): array
    {
        $page = $result->toArray();

        return [
            'page' => (int) ($page['current_page'] ?? 1),
            'per_page' => (int) ($page['per_page'] ?? 20),
            'total' => (int) ($page['total'] ?? 0),
            'total_pages' => (int) ($page['last_page'] ?? 1),
            'has_next' => ! empty($page['next_page_url']),
            'has_prev' => ! empty($page['prev_page_url']),
        ];
    }
}

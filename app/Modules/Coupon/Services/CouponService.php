<?php

declare(strict_types=1);

namespace App\Modules\Coupon\Services;

use App\Api\Seller\Responses\Coupon\CouponResponse;
use App\Api\Seller\Responses\Coupon\CouponStatsResponse;
use App\Api\User\Responses\Coupon\CouponResponse as UserCouponResponse;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\Coupon\Repositories\CouponRepository;
use App\Modules\User\Models\UserCoupon;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Services\CommonService;

class CouponService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly CouponRepository $repository,
    ) {}

    public function getRepository(): CouponRepository
    {
        return $this->repository;
    }

    /**
     * 分页查询商家优惠券列表
     */
    public function paginateByMerchantId(int $merchantId, array $params = []): array
    {
        $condition = ['merchant_id' => $merchantId];

        if (! empty($params['status'])) {
            $condition['status'] = (int) $params['status'];
        }

        $page = (int) ($params['page'] ?? 1);
        $perPage = (int) ($params['per_page'] ?? 20);

        $result = $this->getRepository()->page($condition, $page, $perPage, 'created_at', 'desc');

        if (! empty($result['data'])) {
            $result['data'] = array_map(
                fn (array $item): CouponResponse => $this->toResponse($item),
                $result['data']
            );
        }

        return $this->formatPage($result);
    }

    /**
     * 创建商家优惠券
     */
    public function createForMerchant(int $merchantId, array $data): Coupon
    {
        $couponData = $this->mapStoreData($merchantId, $data);
        $couponId = $this->insertGetId($couponData);

        return Coupon::findOrFail($couponId);
    }

    /**
     * 查询商家优惠券详情
     */
    public function findForMerchant(int $id, int $merchantId): ?Coupon
    {
        return Coupon::where('id', $id)->where('merchant_id', $merchantId)->first();
    }

    /**
     * 更新商家优惠券
     */
    public function updateForMerchant(int $id, int $merchantId, array $data): Coupon
    {
        $coupon = Coupon::where('id', $id)->where('merchant_id', $merchantId)->first();

        if ($coupon === null) {
            throw new BusinessException('优惠券不存在');
        }

        $coupon->update($this->mapUpdateData($data));

        return $coupon->fresh();
    }

    /**
     * 删除商家优惠券
     */
    public function deleteForMerchant(int $id, int $merchantId): bool
    {
        $coupon = Coupon::where('id', $id)->where('merchant_id', $merchantId)->first();

        if ($coupon === null) {
            return false;
        }

        return (bool) $coupon->delete();
    }

    /**
     * 更新优惠券状态
     */
    public function updateStatus(int $id, int $merchantId, int $status): bool
    {
        $affected = Coupon::where('id', $id)->where('merchant_id', $merchantId)->update(['status' => $status]);

        return $affected > 0;
    }

    /**
     * 获取优惠券统计
     */
    public function getStats(int $id, int $merchantId): CouponStatsResponse
    {
        $coupon = Coupon::where('id', $id)->where('merchant_id', $merchantId)->first();

        if ($coupon === null) {
            throw new BusinessException('优惠券不存在');
        }

        $issued = UserCoupon::where('coupon_id', $id)->count();
        $used = UserCoupon::where('coupon_id', $id)->where('status', 1)->count();
        $expired = UserCoupon::where('coupon_id', $id)->where('status', 2)->count();
        $usageRate = $issued > 0 ? round($used / $issued * 100, 2) : 0.0;

        $discountPerUse = $this->calculateDiscountPerUse($coupon);
        $totalDiscountAmount = $used * $discountPerUse;

        $response = new CouponStatsResponse;
        $response->setCouponId($id);
        $response->setTotalIssued((int) $issued);
        $response->setTotalUsed((int) $used);
        $response->setTotalExpired((int) $expired);
        $response->setUsageRate($usageRate);
        $response->setTotalDiscountAmount($totalDiscountAmount);
        $response->setTotalOrderAmount(0);

        return $response;
    }

    /**
     * 获取用户可领取的优惠券列表
     */
    public function getAvailableCoupons(int $userId, array $params = []): array
    {
        $now = now();

        $builder = Coupon::where('status', 1)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->where('remaining_quantity', '>', 0);

        $page = (int) ($params['page'] ?? 1);
        $perPage = (int) ($params['per_page'] ?? 20);

        $result = $builder->orderByDesc('created_at')->paginate($perPage, ['*'], 'page', $page);
        $couponIds = $result->pluck('id')->all();

        $receivedIds = $userId > 0
            ? UserCoupon::where('user_id', $userId)->whereIn('coupon_id', $couponIds)->pluck('coupon_id')->all()
            : [];

        $items = [];
        foreach ($result->items() as $coupon) {
            $items[] = $this->toUserResponse($coupon->toArray(), $receivedIds);
        }

        return [
            'items' => $items,
            'pagination' => $this->buildPagination($result),
        ];
    }

    /**
     * 将优惠券数据转换为商家响应 DTO
     */
    public function toResponse(array $coupon): CouponResponse
    {
        return CouponResponse::from([
            'id' => (int) $coupon['id'],
            'name' => $coupon['name'],
            'type' => (int) $coupon['type'],
            'amount' => $this->resolveAmount($coupon),
            'min_order_amount' => (int) ($coupon['threshold_amount'] ?? 0),
            'total_quantity' => (int) $coupon['total_quantity'],
            'remaining_quantity' => (int) $coupon['remaining_quantity'],
            'start_time' => $coupon['start_time'],
            'end_time' => $coupon['end_time'],
            'status' => (int) $coupon['status'],
            'created_at' => $coupon['created_at'],
        ]);
    }

    /**
     * 将优惠券数据转换为用户响应 DTO
     */
    public function toUserResponse(array $coupon, array $receivedIds = []): UserCouponResponse
    {
        $couponId = (int) $coupon['id'];

        return UserCouponResponse::from([
            'id' => $couponId,
            'name' => $coupon['name'],
            'type' => $this->typeToString((int) $coupon['type']),
            'amount' => $this->resolveAmount($coupon),
            'min_order_amount' => (int) ($coupon['threshold_amount'] ?? 0),
            'description' => $coupon['description'] ?? null,
            'start_time' => $coupon['start_time'],
            'end_time' => $coupon['end_time'],
            'total_count' => (int) $coupon['total_quantity'],
            'received_count' => (int) $coupon['total_quantity'] - (int) $coupon['remaining_quantity'],
            'is_received' => in_array($couponId, $receivedIds, true) ? 1 : 0,
            'scope' => $this->scopeToString((int) ($coupon['scope'] ?? 1)),
        ]);
    }

    private function mapStoreData(int $merchantId, array $data): array
    {
        $type = (int) $data['type'];
        $amount = (int) $data['amount'];
        $minOrderAmount = (int) ($data['min_order_amount'] ?? 0);

        $couponData = [
            'merchant_id' => $merchantId,
            'name' => $data['name'],
            'type' => $type,
            'scope' => $this->resolveScope($data),
            'threshold_amount' => $minOrderAmount,
            'total_quantity' => (int) $data['total_quantity'],
            'remaining_quantity' => (int) $data['total_quantity'],
            'limit_per_user' => (int) ($data['limit_per_user'] ?? 1),
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($type === 2) {
            $couponData['discount_rate'] = $amount / 1000;
            $couponData['discount_amount'] = 0;
        } else {
            $couponData['discount_amount'] = $amount;
            $couponData['discount_rate'] = null;
        }

        return $couponData;
    }

    private function mapUpdateData(array $data): array
    {
        $type = (int) $data['type'];
        $amount = (int) $data['amount'];
        $minOrderAmount = (int) ($data['min_order_amount'] ?? 0);

        $update = [
            'name' => $data['name'],
            'type' => $type,
            'scope' => $this->resolveScope($data),
            'threshold_amount' => $minOrderAmount,
            'total_quantity' => (int) $data['total_quantity'],
            'limit_per_user' => (int) ($data['limit_per_user'] ?? 1),
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => (int) $data['status'],
            'updated_at' => now(),
        ];

        if ($type === 2) {
            $update['discount_rate'] = $amount / 1000;
        } else {
            $update['discount_amount'] = $amount;
        }

        return $update;
    }

    private function resolveScope(array $data): int
    {
        if (! empty($data['applicable_product_ids'])) {
            return 3;
        }

        if (! empty($data['applicable_category_ids'])) {
            return 2;
        }

        return 4;
    }

    private function resolveAmount(array $coupon): int
    {
        $type = (int) ($coupon['type'] ?? 1);

        if ($type === 2 && ! empty($coupon['discount_rate'])) {
            return (int) round((float) $coupon['discount_rate'] * 1000);
        }

        return (int) ($coupon['discount_amount'] ?? 0);
    }

    private function calculateDiscountPerUse(Coupon $coupon): int
    {
        if ((int) $coupon->type === 2) {
            return (int) ($coupon->max_discount_amount ?? 0);
        }

        return (int) $coupon->discount_amount;
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

    private function scopeToString(int $scope): string
    {
        return match ($scope) {
            1 => 'all',
            2 => 'category',
            3 => 'product',
            4 => 'merchant',
            default => 'all',
        };
    }

    private function formatPage(array $page): array
    {
        return [
            'items' => $page['data'] ?? [],
            'pagination' => [
                'page' => (int) ($page['current_page'] ?? 1),
                'per_page' => (int) ($page['per_page'] ?? 20),
                'total' => (int) ($page['total'] ?? 0),
                'total_pages' => (int) ($page['last_page'] ?? 1),
                'has_next' => ! empty($page['next_page_url']),
                'has_prev' => ! empty($page['prev_page_url']),
            ],
        ];
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

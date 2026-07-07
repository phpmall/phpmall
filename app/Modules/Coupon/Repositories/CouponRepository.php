<?php

declare(strict_types=1);

namespace App\Modules\Coupon\Repositories;

use App\Modules\Coupon\Entities\CouponEntity;
use App\Modules\Coupon\Models\Coupon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class CouponRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 CouponEntity
     */
    public function saveEntity(CouponEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?CouponEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return CouponEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?CouponEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return CouponEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('coupons');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Coupon;
    }
}

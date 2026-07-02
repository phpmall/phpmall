<?php

declare(strict_types=1);

namespace App\Modules\User\Repositories;

use App\Modules\User\Entities\UserCouponEntity;
use App\Modules\User\Models\UserCoupon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserCouponRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 UserCouponEntity
     */
    public function saveEntity(UserCouponEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserCouponEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return UserCouponEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserCouponEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return UserCouponEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_coupons');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserCoupon;
    }
}

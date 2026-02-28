<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserBookingEntity;
use App\Bundles\User\Models\UserBooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserBookingRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserBookingRepository $instance = null;

    /**
     * 单例 UserBookingRepository
     */
    public static function getInstance(): UserBookingRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserBookingRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserBookingEntity
     */
    public function saveEntity(UserBookingEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserBookingEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserBookingEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserBookingEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserBookingEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_booking');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserBooking;
    }
}

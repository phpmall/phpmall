<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserAddressEntity;
use App\Bundles\User\Models\UserAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserAddressRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserAddressRepository $instance = null;

    /**
     * 单例 UserAddressRepository
     */
    public static function getInstance(): UserAddressRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserAddressRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserAddressEntity
     */
    public function saveEntity(UserAddressEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserAddressEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserAddressEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserAddressEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserAddressEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_address');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserAddress;
    }
}

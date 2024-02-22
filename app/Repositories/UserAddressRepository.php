<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\UserAddressEntity;
use App\Foundation\Contracts\RepositoryInterface;
use App\Foundation\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class UserAddressRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserAddressRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): UserAddressRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserAddressRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(UserAddressEntity $entity): int
    {
        return $this->save($entity->toArray());
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

        $entity = new UserAddressEntity();
        $entity->setData($data);

        return $entity;
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

        $entity = new UserAddressEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('user_address');
    }
}

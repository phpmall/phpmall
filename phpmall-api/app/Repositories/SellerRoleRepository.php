<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SellerRoleEntity;
use App\Foundation\Contracts\RepositoryInterface;
use App\Foundation\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class SellerRoleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SellerRoleRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): SellerRoleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SellerRoleRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(SellerRoleEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SellerRoleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerRoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SellerRoleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerRoleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('seller_roles');
    }
}

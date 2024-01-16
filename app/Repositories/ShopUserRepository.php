<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ShopUserEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ShopUserRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopUserRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ShopUserRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopUserRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ShopUserEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopUserEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ShopUserEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopUserEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ShopUserEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('shop_users');
    }
}

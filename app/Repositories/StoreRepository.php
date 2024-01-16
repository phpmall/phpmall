<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\StoreEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class StoreRepository extends CurdRepository implements RepositoryInterface
{
    private static ?StoreRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): StoreRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new StoreRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(StoreEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?StoreEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new StoreEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?StoreEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new StoreEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('stores');
    }
}

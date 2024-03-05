<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Entities\AdvertisingEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AdvertisingRepository extends CurdRepository implements RepositoryInterface
{
    private static ?AdvertisingRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): AdvertisingRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdvertisingRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(AdvertisingEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdvertisingEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new AdvertisingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdvertisingEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new AdvertisingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('advertising');
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\RegionEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class RegionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?RegionRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): RegionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new RegionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(RegionEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?RegionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new RegionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?RegionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new RegionEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('regions');
    }
}

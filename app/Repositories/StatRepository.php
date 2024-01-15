<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\StatEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class StatRepository extends CurdRepository implements RepositoryInterface
{
    private static ?StatRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): StatRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new StatRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(StatEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?StatEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new StatEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?StatEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new StatEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('stat');
    }
}

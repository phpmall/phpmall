<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ContentModelEntity;
use App\Foundation\Contracts\RepositoryInterface;
use App\Foundation\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ContentModelRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ContentModelRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ContentModelRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContentModelRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ContentModelEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentModelEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentModelEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentModelEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentModelEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('content_models');
    }
}

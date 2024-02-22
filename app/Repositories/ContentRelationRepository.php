<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ContentRelationEntity;
use App\Foundation\Contracts\RepositoryInterface;
use App\Foundation\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ContentRelationRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ContentRelationRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ContentRelationRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContentRelationRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ContentRelationEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentRelationEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentRelationEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentRelationEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentRelationEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('content_relations');
    }
}

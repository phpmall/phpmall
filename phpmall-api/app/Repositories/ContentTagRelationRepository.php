<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ContentTagRelationEntity;
use App\Foundation\Contracts\RepositoryInterface;
use App\Foundation\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ContentTagRelationRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ContentTagRelationRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ContentTagRelationRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContentTagRelationRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ContentTagRelationEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentTagRelationEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentTagRelationEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentTagRelationEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentTagRelationEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('content_tag_relations');
    }
}

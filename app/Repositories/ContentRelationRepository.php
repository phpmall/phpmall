<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContentRelation;
use App\Models\Entity\ContentRelationEntity;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

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
     * 定义数据数据模型类
     */
    public function model(): ContentRelation
    {
        return new ContentRelation();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContentModel;
use App\Models\Entity\ContentModelEntity;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

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
     * 定义数据数据模型类
     */
    public function model(): ContentModel
    {
        return new ContentModel();
    }
}

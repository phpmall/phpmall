<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContentTag;
use App\Models\Entity\ContentTagEntity;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class ContentTagRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ContentTagRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ContentTagRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContentTagRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ContentTagEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentTagEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentTagEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentTagEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentTagEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ContentTag
    {
        return new ContentTag();
    }
}

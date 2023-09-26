<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Content;
use App\Models\Entity\ContentEntity;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class ContentRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ContentRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ContentRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContentRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ContentEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Content
    {
        return new Content();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContentField;
use App\Models\Entity\ContentFieldEntity;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class ContentFieldRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ContentFieldRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ContentFieldRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContentFieldRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ContentFieldEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentFieldEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentFieldEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentFieldEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentFieldEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ContentField
    {
        return new ContentField();
    }
}

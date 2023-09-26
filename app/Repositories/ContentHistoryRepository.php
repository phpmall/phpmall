<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContentHistory;
use App\Models\Entity\ContentHistoryEntity;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class ContentHistoryRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ContentHistoryRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ContentHistoryRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ContentHistoryRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ContentHistoryEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ContentHistoryEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentHistoryEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ContentHistoryEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ContentHistoryEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ContentHistory
    {
        return new ContentHistory();
    }
}

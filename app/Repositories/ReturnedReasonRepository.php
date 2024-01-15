<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ReturnedReasonEntity;
use App\Models\ReturnedReason;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ReturnedReasonRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ReturnedReasonRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ReturnedReasonRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ReturnedReasonRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ReturnedReasonEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ReturnedReasonEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ReturnedReasonEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ReturnedReasonEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ReturnedReasonEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ReturnedReason
    {
        return new ReturnedReason();
    }
}

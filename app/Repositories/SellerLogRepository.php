<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\SellerLogEntity;
use App\Models\SellerLog;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class SellerLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SellerLogRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): SellerLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SellerLogRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(SellerLogEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SellerLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SellerLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): SellerLog
    {
        return new SellerLog();
    }
}

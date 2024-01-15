<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CouponLog;
use App\Models\Entity\CouponLogEntity;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class CouponLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CouponLogRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CouponLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CouponLogRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(CouponLogEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?CouponLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new CouponLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?CouponLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new CouponLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): CouponLog
    {
        return new CouponLog();
    }
}

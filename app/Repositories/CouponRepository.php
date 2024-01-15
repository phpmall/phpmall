<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\Entity\CouponEntity;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class CouponRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CouponRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CouponRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CouponRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(CouponEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?CouponEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new CouponEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?CouponEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new CouponEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Coupon
    {
        return new Coupon();
    }
}

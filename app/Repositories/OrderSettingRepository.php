<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\OrderSettingEntity;
use App\Models\OrderSetting;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class OrderSettingRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderSettingRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): OrderSettingRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderSettingRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(OrderSettingEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?OrderSettingEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new OrderSettingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?OrderSettingEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new OrderSettingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): OrderSetting
    {
        return new OrderSetting();
    }
}

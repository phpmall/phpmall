<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\SettingEntity;
use App\Models\Setting;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class SettingRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SettingRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): SettingRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SettingRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(SettingEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SettingEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SettingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SettingEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SettingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): Setting
    {
        return new Setting();
    }
}

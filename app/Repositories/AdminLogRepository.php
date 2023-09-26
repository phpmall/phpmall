<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AdminLog;
use App\Models\Entity\AdminLogEntity;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class AdminLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?AdminLogRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): AdminLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdminLogRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(AdminLogEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdminLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new AdminLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdminLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new AdminLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): AdminLog
    {
        return new AdminLog();
    }
}

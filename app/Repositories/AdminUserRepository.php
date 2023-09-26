<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AdminUser;
use App\Models\Entity\AdminUserEntity;
use Focite\Generator\Contracts\RepositoryInterface;
use Focite\Generator\Repositories\CurdRepository;

class AdminUserRepository extends CurdRepository implements RepositoryInterface
{
    private static ?AdminUserRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): AdminUserRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdminUserRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(AdminUserEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdminUserEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new AdminUserEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdminUserEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new AdminUserEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): AdminUser
    {
        return new AdminUser();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\SellerUserEntity;
use App\Models\SellerUser;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class SellerUserRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SellerUserRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): SellerUserRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SellerUserRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(SellerUserEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SellerUserEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerUserEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SellerUserEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerUserEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): SellerUser
    {
        return new SellerUser();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ProductLadderEntity;
use App\Models\ProductLadder;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ProductLadderRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductLadderRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductLadderRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductLadderRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ProductLadderEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductLadderEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductLadderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductLadderEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductLadderEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductLadder
    {
        return new ProductLadder();
    }
}

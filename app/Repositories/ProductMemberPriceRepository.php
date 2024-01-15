<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ProductMemberPriceEntity;
use App\Models\ProductMemberPrice;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ProductMemberPriceRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductMemberPriceRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductMemberPriceRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductMemberPriceRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ProductMemberPriceEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ProductMemberPriceEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductMemberPriceEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ProductMemberPriceEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new ProductMemberPriceEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductMemberPrice
    {
        return new ProductMemberPrice();
    }
}

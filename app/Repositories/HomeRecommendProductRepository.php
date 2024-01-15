<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\HomeRecommendProductEntity;
use App\Models\HomeRecommendProduct;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class HomeRecommendProductRepository extends CurdRepository implements RepositoryInterface
{
    private static ?HomeRecommendProductRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): HomeRecommendProductRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new HomeRecommendProductRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(HomeRecommendProductEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?HomeRecommendProductEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new HomeRecommendProductEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?HomeRecommendProductEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new HomeRecommendProductEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): HomeRecommendProduct
    {
        return new HomeRecommendProduct();
    }
}

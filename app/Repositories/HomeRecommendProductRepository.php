<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\HomeRecommendProduct;
use App\Models\HomeRecommendProductModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

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
    public function saveHomeRecommendProduct(HomeRecommendProduct $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnHomeRecommendProduct(int $id): ?HomeRecommendProduct
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new HomeRecommendProduct();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnHomeRecommendProduct(array $condition): ?HomeRecommendProduct
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new HomeRecommendProduct();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnHomeRecommendProduct(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new HomeRecommendProduct();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnHomeRecommendProduct(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new HomeRecommendProduct();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): HomeRecommendProductModel
    {
        return new HomeRecommendProductModel();
    }
}

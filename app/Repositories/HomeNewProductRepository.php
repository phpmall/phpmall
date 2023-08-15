<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\HomeNewProduct;
use App\Models\HomeNewProductModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class HomeNewProductRepository extends CurdRepository implements RepositoryInterface
{
    private static ?HomeNewProductRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): HomeNewProductRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new HomeNewProductRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveHomeNewProduct(HomeNewProduct $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnHomeNewProduct(int $id): ?HomeNewProduct
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new HomeNewProduct();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnHomeNewProduct(array $condition): ?HomeNewProduct
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new HomeNewProduct();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnHomeNewProduct(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new HomeNewProduct();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnHomeNewProduct(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new HomeNewProduct();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): HomeNewProductModel
    {
        return new HomeNewProductModel();
    }
}

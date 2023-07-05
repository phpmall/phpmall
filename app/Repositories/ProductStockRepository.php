<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ProductStock;
use App\Models\ProductStockModel;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

class ProductStockRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductStockRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductStockRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductStockRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveProductStock(ProductStock $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnProductStock(int $id): ?ProductStock
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new ProductStock();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnProductStock(array $condition): ?ProductStock
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new ProductStock();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnProductStock(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new ProductStock();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnProductStock(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new ProductStock();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductStockModel
    {
        return new ProductStockModel();
    }
}

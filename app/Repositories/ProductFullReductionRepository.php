<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ProductFullReduction;
use App\Models\ProductFullReductionModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class ProductFullReductionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductFullReductionRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductFullReductionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductFullReductionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveProductFullReduction(ProductFullReduction $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnProductFullReduction(int $id): ?ProductFullReduction
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new ProductFullReduction();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnProductFullReduction(array $condition): ?ProductFullReduction
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new ProductFullReduction();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnProductFullReduction(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new ProductFullReduction();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnProductFullReduction(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new ProductFullReduction();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductFullReductionModel
    {
        return new ProductFullReductionModel();
    }
}

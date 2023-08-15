<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\ProductMemberPrice;
use App\Models\ProductMemberPriceModel;

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
    public function saveProductMemberPrice(ProductMemberPrice $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnProductMemberPrice(int $id): ?ProductMemberPrice
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new ProductMemberPrice();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnProductMemberPrice(array $condition): ?ProductMemberPrice
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new ProductMemberPrice();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnProductMemberPrice(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new ProductMemberPrice();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnProductMemberPrice(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new ProductMemberPrice();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductMemberPriceModel
    {
        return new ProductMemberPriceModel();
    }
}

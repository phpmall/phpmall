<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ProductAttributeValue;
use App\Models\ProductAttributeValueModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class ProductAttributeValueRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductAttributeValueRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductAttributeValueRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductAttributeValueRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveProductAttributeValue(ProductAttributeValue $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnProductAttributeValue(int $id): ?ProductAttributeValue
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new ProductAttributeValue();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnProductAttributeValue(array $condition): ?ProductAttributeValue
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new ProductAttributeValue();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnProductAttributeValue(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new ProductAttributeValue();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnProductAttributeValue(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new ProductAttributeValue();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductAttributeValueModel
    {
        return new ProductAttributeValueModel();
    }
}

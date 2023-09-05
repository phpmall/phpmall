<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\ProductAttribute;
use App\Models\ProductAttributeModel;

class ProductAttributeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductAttributeRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductAttributeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductAttributeRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveProductAttribute(ProductAttribute $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnProductAttribute(int $id): ?ProductAttribute
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new ProductAttribute();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnProductAttribute(array $condition): ?ProductAttribute
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new ProductAttribute();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnProductAttribute(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new ProductAttribute();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnProductAttribute(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new ProductAttribute();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductAttributeModel
    {
        return new ProductAttributeModel();
    }
}

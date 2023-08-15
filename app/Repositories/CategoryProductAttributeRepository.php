<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CategoryProductAttributeModel;
use App\Models\Entity\CategoryProductAttribute;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class CategoryProductAttributeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CategoryProductAttributeRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CategoryProductAttributeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CategoryProductAttributeRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveCategoryProductAttribute(CategoryProductAttribute $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnCategoryProductAttribute(int $id): ?CategoryProductAttribute
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new CategoryProductAttribute();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnCategoryProductAttribute(array $condition): ?CategoryProductAttribute
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new CategoryProductAttribute();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnCategoryProductAttribute(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new CategoryProductAttribute();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnCategoryProductAttribute(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new CategoryProductAttribute();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): CategoryProductAttributeModel
    {
        return new CategoryProductAttributeModel();
    }
}

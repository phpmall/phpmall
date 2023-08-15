<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CategoryModel;
use App\Models\Entity\Category;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class CategoryRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CategoryRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CategoryRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CategoryRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveCategory(Category $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnCategory(int $id): ?Category
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new Category();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnCategory(array $condition): ?Category
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new Category();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnCategory(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new Category();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnCategory(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new Category();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): CategoryModel
    {
        return new CategoryModel();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\StoreEmployee;
use App\Models\StoreEmployeeModel;

class StoreEmployeeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?StoreEmployeeRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): StoreEmployeeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new StoreEmployeeRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveStoreEmployee(StoreEmployee $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnStoreEmployee(int $id): ?StoreEmployee
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new StoreEmployee();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnStoreEmployee(array $condition): ?StoreEmployee
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new StoreEmployee();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnStoreEmployee(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new StoreEmployee();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnStoreEmployee(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new StoreEmployee();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): StoreEmployeeModel
    {
        return new StoreEmployeeModel();
    }
}

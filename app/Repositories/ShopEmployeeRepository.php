<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ShopEmployee;
use App\Models\ShopEmployeeModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class ShopEmployeeRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopEmployeeRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ShopEmployeeRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopEmployeeRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveShopEmployee(ShopEmployee $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnShopEmployee(int $id): ?ShopEmployee
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new ShopEmployee();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnShopEmployee(array $condition): ?ShopEmployee
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new ShopEmployee();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnShopEmployee(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new ShopEmployee();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnShopEmployee(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new ShopEmployee();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ShopEmployeeModel
    {
        return new ShopEmployeeModel();
    }
}

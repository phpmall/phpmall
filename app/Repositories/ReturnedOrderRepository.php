<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ReturnedOrder;
use App\Models\ReturnedOrderModel;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

class ReturnedOrderRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ReturnedOrderRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ReturnedOrderRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ReturnedOrderRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveReturnedOrder(ReturnedOrder $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnReturnedOrder(int $id): ?ReturnedOrder
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new ReturnedOrder();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnReturnedOrder(array $condition): ?ReturnedOrder
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new ReturnedOrder();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnReturnedOrder(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new ReturnedOrder();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnReturnedOrder(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new ReturnedOrder();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ReturnedOrderModel
    {
        return new ReturnedOrderModel();
    }
}

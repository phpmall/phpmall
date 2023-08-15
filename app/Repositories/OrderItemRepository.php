<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\OrderItem;
use App\Models\OrderItemModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class OrderItemRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderItemRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): OrderItemRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderItemRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveOrderItem(OrderItem $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnOrderItem(int $id): ?OrderItem
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new OrderItem();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnOrderItem(array $condition): ?OrderItem
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new OrderItem();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnOrderItem(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new OrderItem();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnOrderItem(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new OrderItem();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): OrderItemModel
    {
        return new OrderItemModel();
    }
}

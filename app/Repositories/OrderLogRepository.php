<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\OrderLog;
use App\Models\OrderLogModel;

class OrderLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderLogRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): OrderLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderLogRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveOrderLog(OrderLog $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnOrderLog(int $id): ?OrderLog
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new OrderLog();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnOrderLog(array $condition): ?OrderLog
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new OrderLog();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnOrderLog(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new OrderLog();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnOrderLog(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new OrderLog();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): OrderLogModel
    {
        return new OrderLogModel();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\OrderSetting;
use App\Models\OrderSettingModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class OrderSettingRepository extends CurdRepository implements RepositoryInterface
{
    private static ?OrderSettingRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): OrderSettingRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderSettingRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveOrderSetting(OrderSetting $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnOrderSetting(int $id): ?OrderSetting
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new OrderSetting();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnOrderSetting(array $condition): ?OrderSetting
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new OrderSetting();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnOrderSetting(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new OrderSetting();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnOrderSetting(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new OrderSetting();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): OrderSettingModel
    {
        return new OrderSettingModel();
    }
}

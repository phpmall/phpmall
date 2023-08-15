<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ReturnedReason;
use App\Models\ReturnedReasonModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class ReturnedReasonRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ReturnedReasonRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ReturnedReasonRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ReturnedReasonRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveReturnedReason(ReturnedReason $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnReturnedReason(int $id): ?ReturnedReason
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new ReturnedReason();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnReturnedReason(array $condition): ?ReturnedReason
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new ReturnedReason();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnReturnedReason(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new ReturnedReason();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnReturnedReason(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new ReturnedReason();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ReturnedReasonModel
    {
        return new ReturnedReasonModel();
    }
}

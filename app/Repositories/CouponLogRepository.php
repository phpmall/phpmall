<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CouponLogModel;
use App\Models\Entity\CouponLog;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

class CouponLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CouponLogRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CouponLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CouponLogRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveCouponLog(CouponLog $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnCouponLog(int $id): ?CouponLog
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new CouponLog();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnCouponLog(array $condition): ?CouponLog
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new CouponLog();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnCouponLog(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new CouponLog();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnCouponLog(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new CouponLog();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): CouponLogModel
    {
        return new CouponLogModel();
    }
}

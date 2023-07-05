<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CouponModel;
use App\Models\Entity\Coupon;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

class CouponRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CouponRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CouponRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CouponRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveCoupon(Coupon $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnCoupon(int $id): ?Coupon
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new Coupon();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnCoupon(array $condition): ?Coupon
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new Coupon();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnCoupon(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new Coupon();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnCoupon(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new Coupon();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): CouponModel
    {
        return new CouponModel();
    }
}

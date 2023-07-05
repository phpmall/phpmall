<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CouponProductModel;
use App\Models\Entity\CouponProduct;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

class CouponProductRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CouponProductRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CouponProductRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CouponProductRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveCouponProduct(CouponProduct $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnCouponProduct(int $id): ?CouponProduct
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new CouponProduct();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnCouponProduct(array $condition): ?CouponProduct
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new CouponProduct();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnCouponProduct(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new CouponProduct();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnCouponProduct(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new CouponProduct();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): CouponProductModel
    {
        return new CouponProductModel();
    }
}

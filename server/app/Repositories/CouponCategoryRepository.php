<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\CouponCategoryModel;
use App\Models\Entity\CouponCategory;

class CouponCategoryRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CouponCategoryRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CouponCategoryRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CouponCategoryRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveCouponCategory(CouponCategory $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnCouponCategory(int $id): ?CouponCategory
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new CouponCategory();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnCouponCategory(array $condition): ?CouponCategory
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new CouponCategory();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnCouponCategory(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new CouponCategory();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnCouponCategory(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new CouponCategory();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): CouponCategoryModel
    {
        return new CouponCategoryModel();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\FlashPromotionProduct;
use App\Models\FlashPromotionProductModel;

class FlashPromotionProductRepository extends CurdRepository implements RepositoryInterface
{
    private static ?FlashPromotionProductRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): FlashPromotionProductRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new FlashPromotionProductRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveFlashPromotionProduct(FlashPromotionProduct $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnFlashPromotionProduct(int $id): ?FlashPromotionProduct
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new FlashPromotionProduct();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnFlashPromotionProduct(array $condition): ?FlashPromotionProduct
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new FlashPromotionProduct();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnFlashPromotionProduct(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new FlashPromotionProduct();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnFlashPromotionProduct(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new FlashPromotionProduct();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): FlashPromotionProductModel
    {
        return new FlashPromotionProductModel();
    }
}

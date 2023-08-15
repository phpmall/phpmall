<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\Shop;
use App\Models\ShopModel;

class ShopRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ShopRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveShop(Shop $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnShop(int $id): ?Shop
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new Shop();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnShop(array $condition): ?Shop
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new Shop();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnShop(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new Shop();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnShop(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new Shop();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ShopModel
    {
        return new ShopModel();
    }
}

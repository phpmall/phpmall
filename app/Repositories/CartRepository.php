<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CartModel;
use App\Models\Entity\Cart;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

class CartRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CartRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CartRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CartRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveCart(Cart $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnCart(int $id): ?Cart
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new Cart();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnCart(array $condition): ?Cart
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new Cart();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnCart(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new Cart();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnCart(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new Cart();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): CartModel
    {
        return new CartModel();
    }
}

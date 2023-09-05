<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\Seller;
use App\Models\SellerModel;

class SellerRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SellerRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): SellerRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SellerRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveSeller(Seller $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnSeller(int $id): ?Seller
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new Seller();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnSeller(array $condition): ?Seller
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new Seller();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnSeller(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new Seller();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnSeller(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new Seller();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): SellerModel
    {
        return new SellerModel();
    }
}

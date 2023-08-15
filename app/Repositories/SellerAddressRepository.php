<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\SellerAddress;
use App\Models\SellerAddressModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class SellerAddressRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SellerAddressRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): SellerAddressRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SellerAddressRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveSellerAddress(SellerAddress $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnSellerAddress(int $id): ?SellerAddress
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new SellerAddress();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnSellerAddress(array $condition): ?SellerAddress
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new SellerAddress();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnSellerAddress(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new SellerAddress();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnSellerAddress(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new SellerAddress();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): SellerAddressModel
    {
        return new SellerAddressModel();
    }
}

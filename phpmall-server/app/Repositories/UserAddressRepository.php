<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\UserAddress;
use App\Models\UserAddressModel;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

class UserAddressRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserAddressRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): UserAddressRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserAddressRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveUserAddress(UserAddress $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnUserAddress(int $id): ?UserAddress
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new UserAddress();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnUserAddress(array $condition): ?UserAddress
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new UserAddress();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnUserAddress(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new UserAddress();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnUserAddress(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new UserAddress();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): UserAddressModel
    {
        return new UserAddressModel();
    }
}

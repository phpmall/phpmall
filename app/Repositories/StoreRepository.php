<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\Store;
use App\Models\StoreModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class StoreRepository extends CurdRepository implements RepositoryInterface
{
    private static ?StoreRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): StoreRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new StoreRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveStore(Store $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnStore(int $id): ?Store
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new Store();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnStore(array $condition): ?Store
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new Store();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnStore(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new Store();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnStore(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new Store();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): StoreModel
    {
        return new StoreModel();
    }
}

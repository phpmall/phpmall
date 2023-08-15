<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ProductLadder;
use App\Models\ProductLadderModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class ProductLadderRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ProductLadderRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ProductLadderRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ProductLadderRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveProductLadder(ProductLadder $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnProductLadder(int $id): ?ProductLadder
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new ProductLadder();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnProductLadder(array $condition): ?ProductLadder
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new ProductLadder();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnProductLadder(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new ProductLadder();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnProductLadder(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new ProductLadder();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): ProductLadderModel
    {
        return new ProductLadderModel();
    }
}

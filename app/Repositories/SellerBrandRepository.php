<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\SellerBrand;
use App\Models\SellerBrandModel;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

class SellerBrandRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SellerBrandRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): SellerBrandRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SellerBrandRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveSellerBrand(SellerBrand $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnSellerBrand(int $id): ?SellerBrand
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new SellerBrand();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnSellerBrand(array $condition): ?SellerBrand
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new SellerBrand();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnSellerBrand(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new SellerBrand();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnSellerBrand(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new SellerBrand();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): SellerBrandModel
    {
        return new SellerBrandModel();
    }
}

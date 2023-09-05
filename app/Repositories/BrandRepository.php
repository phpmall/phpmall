<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\BrandModel;
use App\Models\Entity\Brand;

class BrandRepository extends CurdRepository implements RepositoryInterface
{
    private static ?BrandRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): BrandRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new BrandRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveBrand(Brand $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnBrand(int $id): ?Brand
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new Brand();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnBrand(array $condition): ?Brand
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new Brand();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnBrand(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new Brand();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnBrand(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new Brand();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): BrandModel
    {
        return new BrandModel();
    }
}

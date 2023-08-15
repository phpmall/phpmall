<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\HomeBrand;
use App\Models\HomeBrandModel;

class HomeBrandRepository extends CurdRepository implements RepositoryInterface
{
    private static ?HomeBrandRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): HomeBrandRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new HomeBrandRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveHomeBrand(HomeBrand $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnHomeBrand(int $id): ?HomeBrand
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new HomeBrand();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnHomeBrand(array $condition): ?HomeBrand
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new HomeBrand();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnHomeBrand(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new HomeBrand();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnHomeBrand(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new HomeBrand();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): HomeBrandModel
    {
        return new HomeBrandModel();
    }
}

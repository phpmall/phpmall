<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\HomeAdvertisement;
use App\Models\HomeAdvertisementModel;

class HomeAdvertisementRepository extends CurdRepository implements RepositoryInterface
{
    private static ?HomeAdvertisementRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): HomeAdvertisementRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new HomeAdvertisementRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveHomeAdvertisement(HomeAdvertisement $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnHomeAdvertisement(int $id): ?HomeAdvertisement
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new HomeAdvertisement();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnHomeAdvertisement(array $condition): ?HomeAdvertisement
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new HomeAdvertisement();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnHomeAdvertisement(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new HomeAdvertisement();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnHomeAdvertisement(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new HomeAdvertisement();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): HomeAdvertisementModel
    {
        return new HomeAdvertisementModel();
    }
}

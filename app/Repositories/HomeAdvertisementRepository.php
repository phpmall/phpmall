<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Entities\HomeAdvertisementEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

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
    public function saveEntity(HomeAdvertisementEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?HomeAdvertisementEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new HomeAdvertisementEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?HomeAdvertisementEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new HomeAdvertisementEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('home_advertisements');
    }
}

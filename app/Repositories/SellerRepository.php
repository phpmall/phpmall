<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SellerEntity;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

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
    public function saveEntity(SellerEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SellerEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SellerEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SellerEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('sellers');
    }
}

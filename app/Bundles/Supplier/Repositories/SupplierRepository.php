<?php

declare(strict_types=1);

namespace App\Bundles\Supplier\Repositories;

use App\Bundles\Supplier\Entities\SupplierEntity;
use App\Bundles\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SupplierRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SupplierRepository $instance = null;

    /**
     * 单例 SupplierRepository
     */
    public static function getInstance(): SupplierRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SupplierRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 SupplierEntity
     */
    public function saveEntity(SupplierEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SupplierEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new SupplierEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SupplierEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new SupplierEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('supplier');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Supplier;
    }
}

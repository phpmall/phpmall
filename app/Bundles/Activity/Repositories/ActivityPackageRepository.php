<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Repositories;

use App\Bundles\Activity\Entities\ActivityPackageEntity;
use App\Bundles\Activity\Models\ActivityPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ActivityPackageRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ActivityPackageRepository $instance = null;

    /**
     * 单例 ActivityPackageRepository
     */
    public static function getInstance(): ActivityPackageRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ActivityPackageRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ActivityPackageEntity
     */
    public function saveEntity(ActivityPackageEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ActivityPackageEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ActivityPackageEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ActivityPackageEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ActivityPackageEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('activity_package');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ActivityPackage;
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Repositories;

use App\Bundles\Ad\Entities\AdPositionEntity;
use App\Bundles\Ad\Models\AdPosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AdPositionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?AdPositionRepository $instance = null;

    /**
     * 单例 AdPositionRepository
     */
    public static function getInstance(): AdPositionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdPositionRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 AdPositionEntity
     */
    public function saveEntity(AdPositionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdPositionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new AdPositionEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdPositionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new AdPositionEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('ad_position');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new AdPosition;
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Repositories;

use App\Bundles\Ad\Entities\AdCustomEntity;
use App\Bundles\Ad\Models\AdCustom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AdCustomRepository extends CurdRepository implements RepositoryInterface
{
    private static ?AdCustomRepository $instance = null;

    /**
     * 单例 AdCustomRepository
     */
    public static function getInstance(): AdCustomRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdCustomRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 AdCustomEntity
     */
    public function saveEntity(AdCustomEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdCustomEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new AdCustomEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdCustomEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new AdCustomEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('ad_custom');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new AdCustom;
    }
}

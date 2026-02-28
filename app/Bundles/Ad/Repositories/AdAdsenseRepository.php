<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Repositories;

use App\Bundles\Ad\Entities\AdAdsenseEntity;
use App\Bundles\Ad\Models\AdAdsense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AdAdsenseRepository extends CurdRepository implements RepositoryInterface
{
    private static ?AdAdsenseRepository $instance = null;

    /**
     * 单例 AdAdsenseRepository
     */
    public static function getInstance(): AdAdsenseRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdAdsenseRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 AdAdsenseEntity
     */
    public function saveEntity(AdAdsenseEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdAdsenseEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new AdAdsenseEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdAdsenseEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new AdAdsenseEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('ad_adsense');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new AdAdsense;
    }
}

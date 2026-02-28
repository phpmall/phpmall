<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Repositories;

use App\Bundles\Activity\Entities\ActivityAuctionEntity;
use App\Bundles\Activity\Models\ActivityAuction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ActivityAuctionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ActivityAuctionRepository $instance = null;

    /**
     * 单例 ActivityAuctionRepository
     */
    public static function getInstance(): ActivityAuctionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ActivityAuctionRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ActivityAuctionEntity
     */
    public function saveEntity(ActivityAuctionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ActivityAuctionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ActivityAuctionEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ActivityAuctionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ActivityAuctionEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('activity_auction');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ActivityAuction;
    }
}

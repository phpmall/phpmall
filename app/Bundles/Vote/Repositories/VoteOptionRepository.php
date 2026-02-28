<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Repositories;

use App\Bundles\Vote\Entities\VoteOptionEntity;
use App\Bundles\Vote\Models\VoteOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class VoteOptionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?VoteOptionRepository $instance = null;

    /**
     * 单例 VoteOptionRepository
     */
    public static function getInstance(): VoteOptionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new VoteOptionRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 VoteOptionEntity
     */
    public function saveEntity(VoteOptionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?VoteOptionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new VoteOptionEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?VoteOptionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new VoteOptionEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('vote_option');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new VoteOption;
    }
}

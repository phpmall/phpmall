<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Repositories;

use App\Bundles\Vote\Entities\VoteLogEntity;
use App\Bundles\Vote\Models\VoteLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class VoteLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?VoteLogRepository $instance = null;

    /**
     * 单例 VoteLogRepository
     */
    public static function getInstance(): VoteLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new VoteLogRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 VoteLogEntity
     */
    public function saveEntity(VoteLogEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?VoteLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new VoteLogEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?VoteLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new VoteLogEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('vote_log');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new VoteLog;
    }
}

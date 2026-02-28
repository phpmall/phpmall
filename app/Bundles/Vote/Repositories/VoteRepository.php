<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Repositories;

use App\Bundles\Vote\Entities\VoteEntity;
use App\Bundles\Vote\Models\Vote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class VoteRepository extends CurdRepository implements RepositoryInterface
{
    private static ?VoteRepository $instance = null;

    /**
     * 单例 VoteRepository
     */
    public static function getInstance(): VoteRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new VoteRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 VoteEntity
     */
    public function saveEntity(VoteEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?VoteEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new VoteEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?VoteEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new VoteEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('vote');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Vote;
    }
}

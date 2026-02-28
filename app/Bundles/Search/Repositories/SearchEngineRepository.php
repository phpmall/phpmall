<?php

declare(strict_types=1);

namespace App\Bundles\Search\Repositories;

use App\Bundles\Search\Entities\SearchEngineEntity;
use App\Bundles\Search\Models\SearchEngine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SearchEngineRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SearchEngineRepository $instance = null;

    /**
     * 单例 SearchEngineRepository
     */
    public static function getInstance(): SearchEngineRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SearchEngineRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 SearchEngineEntity
     */
    public function saveEntity(SearchEngineEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SearchEngineEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new SearchEngineEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SearchEngineEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new SearchEngineEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('search_engine');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SearchEngine;
    }
}

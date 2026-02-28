<?php

declare(strict_types=1);

namespace App\Bundles\Search\Repositories;

use App\Bundles\Search\Entities\SearchKeywordsEntity;
use App\Bundles\Search\Models\SearchKeywords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SearchKeywordsRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SearchKeywordsRepository $instance = null;

    /**
     * 单例 SearchKeywordsRepository
     */
    public static function getInstance(): SearchKeywordsRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SearchKeywordsRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 SearchKeywordsEntity
     */
    public function saveEntity(SearchKeywordsEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SearchKeywordsEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new SearchKeywordsEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SearchKeywordsEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new SearchKeywordsEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('search_keywords');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SearchKeywords;
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\Article\Repositories;

use App\Bundles\Article\Entities\ArticleCatEntity;
use App\Bundles\Article\Models\ArticleCat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ArticleCatRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ArticleCatRepository $instance = null;

    /**
     * 单例 ArticleCatRepository
     */
    public static function getInstance(): ArticleCatRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ArticleCatRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ArticleCatEntity
     */
    public function saveEntity(ArticleCatEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ArticleCatEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ArticleCatEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ArticleCatEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ArticleCatEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('article_cat');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ArticleCat;
    }
}

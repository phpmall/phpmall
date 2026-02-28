<?php

declare(strict_types=1);

namespace App\Bundles\Article\Repositories;

use App\Bundles\Article\Entities\ArticleEntity;
use App\Bundles\Article\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ArticleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ArticleRepository $instance = null;

    /**
     * 单例 ArticleRepository
     */
    public static function getInstance(): ArticleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ArticleRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ArticleEntity
     */
    public function saveEntity(ArticleEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ArticleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ArticleEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ArticleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ArticleEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('article');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Article;
    }
}

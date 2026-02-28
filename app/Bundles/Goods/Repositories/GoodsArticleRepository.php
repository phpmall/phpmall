<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Repositories;

use App\Bundles\Goods\Entities\GoodsArticleEntity;
use App\Bundles\Goods\Models\GoodsArticle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class GoodsArticleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?GoodsArticleRepository $instance = null;

    /**
     * 单例 GoodsArticleRepository
     */
    public static function getInstance(): GoodsArticleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodsArticleRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 GoodsArticleEntity
     */
    public function saveEntity(GoodsArticleEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?GoodsArticleEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new GoodsArticleEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?GoodsArticleEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new GoodsArticleEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('goods_article');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new GoodsArticle;
    }
}

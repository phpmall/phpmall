<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\ArticleEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class ArticleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ArticleRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): ArticleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ArticleRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(ArticleEntity $entity): int
    {
        return $this->save($entity->toArray());
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

        $entity = new ArticleEntity();
        $entity->setData($data);

        return $entity;
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

        $entity = new ArticleEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('article');
    }
}

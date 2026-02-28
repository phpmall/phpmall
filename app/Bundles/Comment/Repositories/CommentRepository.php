<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Repositories;

use App\Bundles\Comment\Entities\CommentEntity;
use App\Bundles\Comment\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class CommentRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CommentRepository $instance = null;

    /**
     * 单例 CommentRepository
     */
    public static function getInstance(): CommentRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CommentRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 CommentEntity
     */
    public function saveEntity(CommentEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?CommentEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new CommentEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?CommentEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new CommentEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('comment');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Comment;
    }
}

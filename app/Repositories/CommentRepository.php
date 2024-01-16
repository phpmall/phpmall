<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\CommentEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class CommentRepository extends CurdRepository implements RepositoryInterface
{
    private static ?CommentRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): CommentRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new CommentRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(CommentEntity $entity): int
    {
        return $this->save($entity->toArray());
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

        $entity = new CommentEntity();
        $entity->setData($data);

        return $entity;
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

        $entity = new CommentEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('comments');
    }
}

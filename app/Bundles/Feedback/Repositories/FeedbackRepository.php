<?php

declare(strict_types=1);

namespace App\Bundles\Feedback\Repositories;

use App\Bundles\Feedback\Entities\FeedbackEntity;
use App\Bundles\Feedback\Models\Feedback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class FeedbackRepository extends CurdRepository implements RepositoryInterface
{
    private static ?FeedbackRepository $instance = null;

    /**
     * 单例 FeedbackRepository
     */
    public static function getInstance(): FeedbackRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new FeedbackRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 FeedbackEntity
     */
    public function saveEntity(FeedbackEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?FeedbackEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new FeedbackEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?FeedbackEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new FeedbackEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('feedback');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Feedback;
    }
}

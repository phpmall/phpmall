<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\HomeRecommendTopicEntity;
use App\Foundation\Contracts\RepositoryInterface;
use App\Foundation\Repositories\CurdRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class HomeRecommendTopicRepository extends CurdRepository implements RepositoryInterface
{
    private static ?HomeRecommendTopicRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): HomeRecommendTopicRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new HomeRecommendTopicRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(HomeRecommendTopicEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?HomeRecommendTopicEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new HomeRecommendTopicEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?HomeRecommendTopicEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new HomeRecommendTopicEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('home_recommend_topics');
    }
}

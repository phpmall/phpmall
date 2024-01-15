<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\HomeRecommendTopicEntity;
use App\Models\HomeRecommendTopic;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

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
     * 定义数据数据模型类
     */
    public function model(): HomeRecommendTopic
    {
        return new HomeRecommendTopic();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\HomeRecommendTopic;
use App\Models\HomeRecommendTopicModel;
use Focite\Builder\Contracts\RepositoryInterface;
use Focite\Builder\Repositories\CurdRepository;

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
    public function saveHomeRecommendTopic(HomeRecommendTopic $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnHomeRecommendTopic(int $id): ?HomeRecommendTopic
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new HomeRecommendTopic();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnHomeRecommendTopic(array $condition): ?HomeRecommendTopic
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new HomeRecommendTopic();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnHomeRecommendTopic(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new HomeRecommendTopic();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnHomeRecommendTopic(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new HomeRecommendTopic();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): HomeRecommendTopicModel
    {
        return new HomeRecommendTopicModel();
    }
}

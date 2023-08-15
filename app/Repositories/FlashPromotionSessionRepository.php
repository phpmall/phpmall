<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\FlashPromotionSession;
use App\Models\FlashPromotionSessionModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class FlashPromotionSessionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?FlashPromotionSessionRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): FlashPromotionSessionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new FlashPromotionSessionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveFlashPromotionSession(FlashPromotionSession $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnFlashPromotionSession(int $id): ?FlashPromotionSession
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new FlashPromotionSession();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnFlashPromotionSession(array $condition): ?FlashPromotionSession
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new FlashPromotionSession();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnFlashPromotionSession(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new FlashPromotionSession();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnFlashPromotionSession(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new FlashPromotionSession();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): FlashPromotionSessionModel
    {
        return new FlashPromotionSessionModel();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Entity\FlashPromotionLog;
use App\Models\FlashPromotionLogModel;

class FlashPromotionLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?FlashPromotionLogRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): FlashPromotionLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new FlashPromotionLogRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveFlashPromotionLog(FlashPromotionLog $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnFlashPromotionLog(int $id): ?FlashPromotionLog
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new FlashPromotionLog();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnFlashPromotionLog(array $condition): ?FlashPromotionLog
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new FlashPromotionLog();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnFlashPromotionLog(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new FlashPromotionLog();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnFlashPromotionLog(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new FlashPromotionLog();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): FlashPromotionLogModel
    {
        return new FlashPromotionLogModel();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\FlashPromotionLogEntity;
use App\Models\FlashPromotionLog;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

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
    public function saveEntity(FlashPromotionLogEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?FlashPromotionLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new FlashPromotionLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?FlashPromotionLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new FlashPromotionLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): FlashPromotionLog
    {
        return new FlashPromotionLog();
    }
}

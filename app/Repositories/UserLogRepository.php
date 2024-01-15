<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\UserLogEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Generator\Contracts\RepositoryInterface;
use Juling\Generator\Repositories\CurdRepository;

class UserLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserLogRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): UserLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserLogRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(UserLogEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new UserLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new UserLogEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('user_log');
    }
}

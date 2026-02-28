<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Repositories;

use App\Bundles\Admin\Entities\AdminLogEntity;
use App\Bundles\Admin\Models\AdminLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AdminLogRepository extends CurdRepository implements RepositoryInterface
{
    private static ?AdminLogRepository $instance = null;

    /**
     * 单例 AdminLogRepository
     */
    public static function getInstance(): AdminLogRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdminLogRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 AdminLogEntity
     */
    public function saveEntity(AdminLogEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdminLogEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new AdminLogEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdminLogEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new AdminLogEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('admin_log');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new AdminLog;
    }
}

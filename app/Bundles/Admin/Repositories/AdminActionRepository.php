<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Repositories;

use App\Bundles\Admin\Entities\AdminActionEntity;
use App\Bundles\Admin\Models\AdminAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AdminActionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?AdminActionRepository $instance = null;

    /**
     * 单例 AdminActionRepository
     */
    public static function getInstance(): AdminActionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdminActionRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 AdminActionEntity
     */
    public function saveEntity(AdminActionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AdminActionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new AdminActionEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AdminActionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new AdminActionEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('admin_action');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new AdminAction;
    }
}

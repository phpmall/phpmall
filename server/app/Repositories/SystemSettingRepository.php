<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\SystemSettingEntity;
use App\Models\SystemSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemSettingRepository extends CurdRepository implements RepositoryInterface
{
    private static ?SystemSettingRepository $instance = null;

    /**
     * 单例 SystemSettingRepository
     */
    public static function getInstance(): SystemSettingRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new SystemSettingRepository();
        }

        return self::$instance;
    }

    /**
     * 添加 SystemSettingEntity
     */
    public function saveEntity(SystemSettingEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemSettingEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemSettingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemSettingEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new SystemSettingEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_settings');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemSetting();
    }
}

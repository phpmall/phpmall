<?php

declare(strict_types=1);

namespace App\Modules\Notification\Repositories;

use App\Modules\Notification\Entities\NotificationEntity;
use App\Modules\Notification\Models\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class NotificationRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 NotificationEntity
     */
    public function saveEntity(NotificationEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?NotificationEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return NotificationEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?NotificationEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return NotificationEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('notifications');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Notification;
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Message\Repositories;

use App\Modules\Message\Entities\MessageEntity;
use App\Modules\Message\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MessageRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MessageEntity
     */
    public function saveEntity(MessageEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MessageEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MessageEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MessageEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MessageEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('messages');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Message;
    }
}

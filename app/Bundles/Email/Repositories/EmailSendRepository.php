<?php

declare(strict_types=1);

namespace App\Bundles\Email\Repositories;

use App\Bundles\Email\Entities\EmailSendEntity;
use App\Bundles\Email\Models\EmailSend;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class EmailSendRepository extends CurdRepository implements RepositoryInterface
{
    private static ?EmailSendRepository $instance = null;

    /**
     * 单例 EmailSendRepository
     */
    public static function getInstance(): EmailSendRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new EmailSendRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 EmailSendEntity
     */
    public function saveEntity(EmailSendEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?EmailSendEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new EmailSendEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?EmailSendEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new EmailSendEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('email_send');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new EmailSend;
    }
}

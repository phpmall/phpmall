<?php

declare(strict_types=1);

namespace App\Bundles\Email\Repositories;

use App\Bundles\Email\Entities\EmailSubscriberEntity;
use App\Bundles\Email\Models\EmailSubscriber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class EmailSubscriberRepository extends CurdRepository implements RepositoryInterface
{
    private static ?EmailSubscriberRepository $instance = null;

    /**
     * 单例 EmailSubscriberRepository
     */
    public static function getInstance(): EmailSubscriberRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new EmailSubscriberRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 EmailSubscriberEntity
     */
    public function saveEntity(EmailSubscriberEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?EmailSubscriberEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new EmailSubscriberEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?EmailSubscriberEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new EmailSubscriberEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('email_subscriber');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new EmailSubscriber;
    }
}

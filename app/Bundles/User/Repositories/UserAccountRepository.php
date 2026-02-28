<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserAccountEntity;
use App\Bundles\User\Models\UserAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserAccountRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserAccountRepository $instance = null;

    /**
     * 单例 UserAccountRepository
     */
    public static function getInstance(): UserAccountRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserAccountRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserAccountEntity
     */
    public function saveEntity(UserAccountEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserAccountEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserAccountEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserAccountEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserAccountEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_account');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserAccount;
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserCartEntity;
use App\Bundles\User\Models\UserCart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserCartRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserCartRepository $instance = null;

    /**
     * 单例 UserCartRepository
     */
    public static function getInstance(): UserCartRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserCartRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserCartEntity
     */
    public function saveEntity(UserCartEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserCartEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserCartEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserCartEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserCartEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_cart');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserCart;
    }
}

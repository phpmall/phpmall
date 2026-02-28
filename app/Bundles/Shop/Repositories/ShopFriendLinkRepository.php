<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopFriendLinkEntity;
use App\Bundles\Shop\Models\ShopFriendLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopFriendLinkRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopFriendLinkRepository $instance = null;

    /**
     * 单例 ShopFriendLinkRepository
     */
    public static function getInstance(): ShopFriendLinkRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopFriendLinkRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopFriendLinkEntity
     */
    public function saveEntity(ShopFriendLinkEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopFriendLinkEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopFriendLinkEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopFriendLinkEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopFriendLinkEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_friend_link');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopFriendLink;
    }
}

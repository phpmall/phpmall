<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Repositories;

use App\Bundles\Shop\Entities\ShopCardEntity;
use App\Bundles\Shop\Models\ShopCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopCardRepository extends CurdRepository implements RepositoryInterface
{
    private static ?ShopCardRepository $instance = null;

    /**
     * 单例 ShopCardRepository
     */
    public static function getInstance(): ShopCardRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new ShopCardRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 ShopCardEntity
     */
    public function saveEntity(ShopCardEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopCardEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new ShopCardEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopCardEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new ShopCardEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shop_card');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new ShopCard;
    }
}

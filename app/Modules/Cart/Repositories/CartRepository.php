<?php

declare(strict_types=1);

namespace App\Modules\Cart\Repositories;

use App\Modules\Cart\Entities\CartEntity;
use App\Modules\Cart\Models\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class CartRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 CartEntity
     */
    public function saveEntity(CartEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?CartEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return CartEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?CartEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return CartEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('carts');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Cart;
    }
}

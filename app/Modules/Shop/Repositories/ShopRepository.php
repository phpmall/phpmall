<?php

declare(strict_types=1);

namespace App\Modules\Shop\Repositories;

use App\Modules\Shop\Entities\ShopEntity;
use App\Modules\Shop\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class ShopRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 ShopEntity
     */
    public function saveEntity(ShopEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?ShopEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return ShopEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?ShopEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return ShopEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('shops');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new Shop;
    }
}

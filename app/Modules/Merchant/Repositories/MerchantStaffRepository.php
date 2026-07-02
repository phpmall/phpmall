<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Repositories;

use App\Modules\Merchant\Entities\MerchantStaffEntity;
use App\Modules\Merchant\Models\MerchantStaff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class MerchantStaffRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 MerchantStaffEntity
     */
    public function saveEntity(MerchantStaffEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?MerchantStaffEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return MerchantStaffEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?MerchantStaffEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return MerchantStaffEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('merchant_staffs');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new MerchantStaff;
    }
}

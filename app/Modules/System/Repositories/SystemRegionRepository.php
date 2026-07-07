<?php

declare(strict_types=1);

namespace App\Modules\System\Repositories;

use App\Modules\System\Entities\SystemRegionEntity;
use App\Modules\System\Models\SystemRegion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class SystemRegionRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 添加 SystemRegionEntity
     */
    public function saveEntity(SystemRegionEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?SystemRegionEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return SystemRegionEntity::from($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?SystemRegionEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return SystemRegionEntity::from($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('system_regions');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new SystemRegion;
    }
}

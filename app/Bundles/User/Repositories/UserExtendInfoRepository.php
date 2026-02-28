<?php

declare(strict_types=1);

namespace App\Bundles\User\Repositories;

use App\Bundles\User\Entities\UserExtendInfoEntity;
use App\Bundles\User\Models\UserExtendInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class UserExtendInfoRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserExtendInfoRepository $instance = null;

    /**
     * 单例 UserExtendInfoRepository
     */
    public static function getInstance(): UserExtendInfoRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserExtendInfoRepository;
        }

        return self::$instance;
    }

    /**
     * 添加 UserExtendInfoEntity
     */
    public function saveEntity(UserExtendInfoEntity $entity): int
    {
        return $this->save($entity->toEntity());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?UserExtendInfoEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        return new UserExtendInfoEntity($data);
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?UserExtendInfoEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        return new UserExtendInfoEntity($data);
    }

    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('user_extend_info');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new UserExtendInfo;
    }
}

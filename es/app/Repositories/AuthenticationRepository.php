<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\AuthenticationEntity;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class AuthenticationRepository extends CurdRepository implements RepositoryInterface
{
    private static ?AuthenticationRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): AuthenticationRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new AuthenticationRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveEntity(AuthenticationEntity $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneById(int $id): ?AuthenticationEntity
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $entity = new AuthenticationEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOne(array $condition = []): ?AuthenticationEntity
    {
        $data = $this->find($condition);
        if (empty($data)) {
            return null;
        }

        $entity = new AuthenticationEntity();
        $entity->setData($data);

        return $entity;
    }

    /**
     * 定义数据表查询构造器
     */
    public function model(): Builder
    {
        return DB::table('authentications');
    }
}

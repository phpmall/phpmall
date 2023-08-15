<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\UserRole;
use App\Models\UserRoleModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class UserRoleRepository extends CurdRepository implements RepositoryInterface
{
    private static ?UserRoleRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): UserRoleRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserRoleRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function saveUserRole(UserRole $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnUserRole(int $id): ?UserRole
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new UserRole();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnUserRole(array $condition): ?UserRole
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new UserRole();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnUserRole(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new UserRole();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnUserRole(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new UserRole();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): UserRoleModel
    {
        return new UserRoleModel();
    }
}

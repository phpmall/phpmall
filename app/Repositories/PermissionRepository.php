<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entity\Permission;
use App\Models\PermissionModel;
use App\Contracts\RepositoryInterface;
use App\Repositories\CurdRepository;

class PermissionRepository extends CurdRepository implements RepositoryInterface
{
    private static ?PermissionRepository $instance = null;

    /**
     * 单例
     */
    public static function getInstance(): PermissionRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new PermissionRepository();
        }

        return self::$instance;
    }

    /**
     * 添加
     */
    public function savePermission(Permission $entity): int
    {
        return $this->save($entity->toArray());
    }

    /**
     * 按照ID查询返回对象
     */
    public function findOneByIdReturnPermission(int $id): ?Permission
    {
        $data = $this->findById($id);
        if (empty($data)) {
            return null;
        }

        $output = new Permission();
        $output->setData($data);

        return $output;
    }

    /**
     * 按照条件查询返回对象
     */
    public function findOneByWhereReturnPermission(array $condition): ?Permission
    {
        $data = $this->findByWhere($condition);
        if (empty($data)) {
            return null;
        }

        $output = new Permission();
        $output->setData($data);

        return $output;
    }

    /**
     * 查询列表
     */
    public function findAllReturnPermission(array $condition = [], string $order = 'id', string $sort = 'asc'): array
    {
        $result = $this->findAll($condition, $order, $sort);
        if (empty($result)) {
            return [];
        }

        foreach ($result as $key => $item) {
            $output = new Permission();
            $output->setData($item);
            $result[$key] = $output;
        }

        return $result;
    }

    /**
     * 分页查询
     */
    public function pageReturnPermission(array $condition, int $page, int $pageSize): array
    {
        $result = $this->page($condition, $page, $pageSize);

        foreach ($result['data'] as $key => $item) {
            $output = new Permission();
            $output->setData($item);
            $result['data'][$key] = $output;
        }

        return $result;
    }

    /**
     * 定义数据数据模型类
     */
    public function model(): PermissionModel
    {
        return new PermissionModel();
    }
}

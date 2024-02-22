<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ManagerRoleEntity')]
class ManagerRoleEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'manager_id', description: '用户ID', type: 'integer')]
    protected int $manager_id;

    #[OA\Property(property: 'role_id', description: '角色ID', type: 'integer')]
    protected int $role_id;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取用户ID
     */
    public function getManagerId(): int
    {
        return $this->manager_id;
    }

    /**
     * 设置用户ID
     */
    public function setManagerId(int $manager_id): void
    {
        $this->manager_id = $manager_id;
    }

    /**
     * 获取角色ID
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }

    /**
     * 设置角色ID
     */
    public function setRoleId(int $role_id): void
    {
        $this->role_id = $role_id;
    }
}

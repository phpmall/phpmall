<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RolePermissionEntity')]
class RolePermissionEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'role_id', description: '角色ID', type: 'integer')]
    protected int $role_id;

    #[OA\Property(property: 'permission_id', description: '权限资源ID', type: 'integer')]
    protected int $permission_id;

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

    /**
     * 获取权限资源ID
     */
    public function getPermissionId(): int
    {
        return $this->permission_id;
    }

    /**
     * 设置权限资源ID
     */
    public function setPermissionId(int $permission_id): void
    {
        $this->permission_id = $permission_id;
    }
}

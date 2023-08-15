<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RolePermissionSchema')]
class RolePermission
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'role_id', description: '角色ID', type: 'int')]
    protected int $roleId;

    #[OA\Property(property: 'permission_id', description: '权限ID', type: 'int')]
    protected int $permissionId;

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
        return $this->roleId;
    }

    /**
     * 设置角色ID
     */
    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    /**
     * 获取权限ID
     */
    public function getPermissionId(): int
    {
        return $this->permissionId;
    }

    /**
     * 设置权限ID
     */
    public function setPermissionId(int $permissionId): void
    {
        $this->permissionId = $permissionId;
    }
}

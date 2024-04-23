<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemRolePermissionEntity')]
class SystemRolePermissionEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'system_role_id', description: '系统员工角色ID', type: 'integer')]
    protected int $system_role_id;

    #[OA\Property(property: 'system_permission_id', description: '系统权限资源ID', type: 'integer')]
    protected int $system_permission_id;

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
     * 获取系统员工角色ID
     */
    public function getSystemRoleId(): int
    {
        return $this->system_role_id;
    }

    /**
     * 设置系统员工角色ID
     */
    public function setSystemRoleId(int $system_role_id): void
    {
        $this->system_role_id = $system_role_id;
    }

    /**
     * 获取系统权限资源ID
     */
    public function getSystemPermissionId(): int
    {
        return $this->system_permission_id;
    }

    /**
     * 设置系统权限资源ID
     */
    public function setSystemPermissionId(int $system_permission_id): void
    {
        $this->system_permission_id = $system_permission_id;
    }
}

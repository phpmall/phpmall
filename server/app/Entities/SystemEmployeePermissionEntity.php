<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemEmployeePermissionEntity')]
class SystemEmployeePermissionEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'system_employee_id', description: '员工ID', type: 'integer')]
    protected int $system_employee_id;

    #[OA\Property(property: 'system_permission_id', description: '权限资源ID', type: 'integer')]
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
     * 获取员工ID
     */
    public function getSystemEmployeeId(): int
    {
        return $this->system_employee_id;
    }

    /**
     * 设置员工ID
     */
    public function setSystemEmployeeId(int $system_employee_id): void
    {
        $this->system_employee_id = $system_employee_id;
    }

    /**
     * 获取权限资源ID
     */
    public function getSystemPermissionId(): int
    {
        return $this->system_permission_id;
    }

    /**
     * 设置权限资源ID
     */
    public function setSystemPermissionId(int $system_permission_id): void
    {
        $this->system_permission_id = $system_permission_id;
    }
}

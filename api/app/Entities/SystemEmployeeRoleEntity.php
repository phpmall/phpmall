<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemEmployeeRoleEntity')]
class SystemEmployeeRoleEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'system_employee_id', description: '用户ID', type: 'integer')]
    protected int $system_employee_id;

    #[OA\Property(property: 'system_role_id', description: '角色ID', type: 'integer')]
    protected int $system_role_id;

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
    public function getSystemEmployeeId(): int
    {
        return $this->system_employee_id;
    }

    /**
     * 设置用户ID
     */
    public function setSystemEmployeeId(int $system_employee_id): void
    {
        $this->system_employee_id = $system_employee_id;
    }

    /**
     * 获取角色ID
     */
    public function getSystemRoleId(): int
    {
        return $this->system_role_id;
    }

    /**
     * 设置角色ID
     */
    public function setSystemRoleId(int $system_role_id): void
    {
        $this->system_role_id = $system_role_id;
    }
}

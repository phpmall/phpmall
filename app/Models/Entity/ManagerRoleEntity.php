<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ManagerRoleEntity')]
class ManagerRoleEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'manager_id', description: '用户ID', type: 'integer')]
    protected int $managerId;

    #[OA\Property(property: 'role_id', description: '角色ID', type: 'integer')]
    protected int $roleId;

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
        return $this->managerId;
    }

    /**
     * 设置用户ID
     */
    public function setManagerId(int $managerId): void
    {
        $this->managerId = $managerId;
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
}

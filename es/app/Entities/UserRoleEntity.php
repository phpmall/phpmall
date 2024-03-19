<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserRoleEntity')]
class UserRoleEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $user_id;

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
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
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

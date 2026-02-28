<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminRoleEntity')]
class AdminRoleEntity
{
    use DTOHelper;

    const string getRoleId = 'role_id';

    const string getRoleName = 'role_name'; // 角色名称

    const string getActionList = 'action_list'; // 权限列表

    const string getRoleDescribe = 'role_describe'; // 角色描述

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'roleId', description: '', type: 'integer')]
    private int $roleId;

    #[OA\Property(property: 'roleName', description: '角色名称', type: 'string')]
    private string $roleName;

    #[OA\Property(property: 'actionList', description: '权限列表', type: 'string')]
    private string $actionList;

    #[OA\Property(property: 'roleDescribe', description: '角色描述', type: 'string')]
    private string $roleDescribe;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getRoleId(): int
    {
        return $this->roleId;
    }

    /**
     * 设置
     */
    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    /**
     * 获取角色名称
     */
    public function getRoleName(): string
    {
        return $this->roleName;
    }

    /**
     * 设置角色名称
     */
    public function setRoleName(string $roleName): void
    {
        $this->roleName = $roleName;
    }

    /**
     * 获取权限列表
     */
    public function getActionList(): string
    {
        return $this->actionList;
    }

    /**
     * 设置权限列表
     */
    public function setActionList(string $actionList): void
    {
        $this->actionList = $actionList;
    }

    /**
     * 获取角色描述
     */
    public function getRoleDescribe(): string
    {
        return $this->roleDescribe;
    }

    /**
     * 设置角色描述
     */
    public function setRoleDescribe(string $roleDescribe): void
    {
        $this->roleDescribe = $roleDescribe;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedTime(): string
    {
        return $this->createdTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedTime(string $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedTime(string $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}

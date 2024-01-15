<?php

declare(strict_types=1);

namespace App\Foundation\Services;

use App\Services\PermissionService;
use App\Services\RolePermissionService;
use App\Services\RoleService;

class PrivilegeService
{
    /**
     * 获取管理员角色
     */
    public function getAdminRoles(int $adminId): array
    {
        $adminRoleService = new RoleService();
        $roleIds = $adminRoleService->pluck('role_id', [
            'user_id' => $adminId
        ]);

        // 检测有效角色状态
        $roleService = new RoleService();

        return $roleService->getList([
            ['id', 'in', $roleIds],
            ['status', '=', 1],
        ]);
    }

    /**
     * 获取管理员角色ID
     */
    public function getAdminRoleIds(int $adminId): array
    {
        $roles = $this->getAdminRoles($adminId);

        return array_column($roles, 'id');
    }

    /**
     * 获取角色权限
     */
    public function getRolePermission(array $roleIds): array
    {
        $rolePermissionService = new RolePermissionService();
        $permissions = $rolePermissionService->pluck('permission_id', [['role_id', 'in', $roleIds]]);

        // 检测有效权限状态
        $permissionService = new PermissionService();

        return $permissionService->pluck('id', [
            ['id', 'in', $permissions],
            ['status', '=', 1],
        ]);
    }

    /**
     * 获取管理员权限
     */
    public function getAdminPermission(int $adminId): array
    {
        $adminPermissionService = new AdminPermissionService();
        $permissions = $adminPermissionService->pluck('permission_id', ['admin_id' => $adminId]);

        // 检测有效权限状态
        $permissionService = new PermissionService();

        return $permissionService->pluck('id', [
            ['id', 'in', $permissions],
            ['status', '=', 1],
        ]);
    }

    /**
     * 获取管理员权限集合
     */
    public function getPermissionByAdminId(int $adminId): array
    {
        $permissionService = new PermissionService();
        $adminService = new AdminService();
        $admin = $adminService->getById($adminId);

        // 返回全部权限
        if (intval($admin['super_admin']) === 1) {
            return $permissionService->getList();
        }

        $adminRoleIds = $this->getAdminRoleIds($adminId);
        $rolePermission = $this->getRolePermission($adminRoleIds);
        $adminPermission = $this->getAdminPermission($adminId);
        $permissionIds = array_merge($rolePermission, $adminPermission);

        return $permissionService->listByIds(array_unique($permissionIds));
    }

    /**
     * 管理员访问权限校验
     */
    public function check(int $adminId, string $pathInfo): bool
    {
        $permission = $this->getPermissionByAdminId($adminId);

        $paths = array_column($permission, 'path');

        return in_array($pathInfo, $paths);
    }
}

import request from '@/utils/request'
import type { IPermissionQueryRequest,
IPermissionQueryResponse,
IPermissionCreateRequest,
IPermissionResponse,
IPermissionUpdateRequest,
IPermissionDestroyResponse,
IRoleQueryRequest,
IRoleQueryResponse,
IRoleCreateRequest,
IRoleResponse,
IRoleUpdateRequest,
IRoleDestroyResponse,
IRolePermissionQueryRequest,
IRolePermissionQueryResponse,
IRolePermissionCreateRequest,
IRolePermissionResponse,
IRolePermissionUpdateRequest,
IRolePermissionDestroyResponse,
IUserQueryRequest,
IUserQueryResponse,
IUserCreateRequest,
IUserResponse,
IUserUpdateRequest,
IUserDestroyResponse,
IUserLogQueryRequest,
IUserLogQueryResponse,
IUserLogCreateRequest,
IUserLogResponse,
IUserLogUpdateRequest,
IUserLogDestroyResponse,
IUserPermissionQueryRequest,
IUserPermissionQueryResponse,
IUserPermissionCreateRequest,
IUserPermissionResponse,
IUserPermissionUpdateRequest,
IUserPermissionDestroyResponse,
IUserRoleQueryRequest,
IUserRoleQueryResponse,
IUserRoleCreateRequest,
IUserRoleResponse,
IUserRoleUpdateRequest,
IUserRoleDestroyResponse,
IAddressResponse } from '@/types/manager.d'

// [运营中心] 运营首页
export const dashboardService = (): Promise<any> => {
    return request({
        url: 'manager/dashboard',
        method: 'get'
    })
}

// [运营中心] 获取管理菜单
export const menuService = (): Promise<any> => {
    return request({
        url: 'manager/menu',
        method: 'get'
    })
}

// [运营中心] 获取系统消息
export const messageService = (): Promise<any> => {
    return request({
        url: 'manager/message',
        method: 'get'
    })
}

// [运营中心] 管理员信息
export const profileService = (): Promise<any> => {
    return request({
        url: 'manager/profile',
        method: 'get'
    })
}

// [运营中心] 修改密码
export const passwordService = (): Promise<any> => {
    return request({
        url: 'manager/password',
        method: 'post'
    })
}

// [运营中心] 注销登录
export const logoutService = (): Promise<any> => {
    return request({
        url: 'manager/logout',
        method: 'post'
    })
}

// [管理员] 管理员接口
export const managerService = (): Promise<any> => {
    return request({
        url: 'manager/manager',
        method: 'get'
    })
}

// [权限资源模块] 查询列表接口
export const permissionQueryService = (page: number, pageSize: number, formData: IPermissionQueryRequest): Promise<IPermissionQueryResponse> => {
    return request({
        url: 'manager/permission/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [权限资源模块] 新增接口
export const permissionCreateService = (formData: IPermissionCreateRequest): Promise<IPermissionResponse> => {
    return request({
        url: 'manager/permission/create',
        method: 'post',
        data: formData
    })
}

// [权限资源模块] 获取详情接口
export const permissionShowService = (id: number): Promise<IPermissionResponse> => {
    return request({
        url: 'manager/permission/show',
        method: 'get',
        params: {id}
    })
}

// [权限资源模块] 更新接口
export const permissionUpdateService = (formData: IPermissionUpdateRequest): Promise<IPermissionResponse> => {
    return request({
        url: 'manager/permission/update',
        method: 'put',
        data: formData
    })
}

// [权限资源模块] 删除接口
export const permissionDestroyService = (id: number): Promise<IPermissionDestroyResponse> => {
    return request({
        url: 'manager/permission/destroy',
        method: 'delete',
        params: {id}
    })
}

// [用户角色模块] 查询列表接口
export const roleQueryService = (page: number, pageSize: number, formData: IRoleQueryRequest): Promise<IRoleQueryResponse> => {
    return request({
        url: 'manager/role/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [用户角色模块] 新增接口
export const roleCreateService = (formData: IRoleCreateRequest): Promise<IRoleResponse> => {
    return request({
        url: 'manager/role/create',
        method: 'post',
        data: formData
    })
}

// [用户角色模块] 获取详情接口
export const roleShowService = (id: number): Promise<IRoleResponse> => {
    return request({
        url: 'manager/role/show',
        method: 'get',
        params: {id}
    })
}

// [用户角色模块] 更新接口
export const roleUpdateService = (formData: IRoleUpdateRequest): Promise<IRoleResponse> => {
    return request({
        url: 'manager/role/update',
        method: 'put',
        data: formData
    })
}

// [用户角色模块] 删除接口
export const roleDestroyService = (id: number): Promise<IRoleDestroyResponse> => {
    return request({
        url: 'manager/role/destroy',
        method: 'delete',
        params: {id}
    })
}

// [角色资源权限模块] 查询列表接口
export const rolePermissionQueryService = (page: number, pageSize: number, formData: IRolePermissionQueryRequest): Promise<IRolePermissionQueryResponse> => {
    return request({
        url: 'manager/rolePermission/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [角色资源权限模块] 新增接口
export const rolePermissionCreateService = (formData: IRolePermissionCreateRequest): Promise<IRolePermissionResponse> => {
    return request({
        url: 'manager/rolePermission/create',
        method: 'post',
        data: formData
    })
}

// [角色资源权限模块] 获取详情接口
export const rolePermissionShowService = (id: number): Promise<IRolePermissionResponse> => {
    return request({
        url: 'manager/rolePermission/show',
        method: 'get',
        params: {id}
    })
}

// [角色资源权限模块] 更新接口
export const rolePermissionUpdateService = (formData: IRolePermissionUpdateRequest): Promise<IRolePermissionResponse> => {
    return request({
        url: 'manager/rolePermission/update',
        method: 'put',
        data: formData
    })
}

// [角色资源权限模块] 删除接口
export const rolePermissionDestroyService = (id: number): Promise<IRolePermissionDestroyResponse> => {
    return request({
        url: 'manager/rolePermission/destroy',
        method: 'delete',
        params: {id}
    })
}

// [用户模块] 查询列表接口
export const userQueryService = (page: number, pageSize: number, formData: IUserQueryRequest): Promise<IUserQueryResponse> => {
    return request({
        url: 'manager/user/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [用户模块] 新增接口
export const userCreateService = (formData: IUserCreateRequest): Promise<IUserResponse> => {
    return request({
        url: 'manager/user/create',
        method: 'post',
        data: formData
    })
}

// [用户模块] 获取详情接口
export const userShowService = (id: number): Promise<IUserResponse> => {
    return request({
        url: 'manager/user/show',
        method: 'get',
        params: {id}
    })
}

// [用户模块] 更新接口
export const userUpdateService = (formData: IUserUpdateRequest): Promise<IUserResponse> => {
    return request({
        url: 'manager/user/update',
        method: 'put',
        data: formData
    })
}

// [用户模块] 删除接口
export const userDestroyService = (id: number): Promise<IUserDestroyResponse> => {
    return request({
        url: 'manager/user/destroy',
        method: 'delete',
        params: {id}
    })
}

// [用户日志模块] 查询列表接口
export const userLogQueryService = (page: number, pageSize: number, formData: IUserLogQueryRequest): Promise<IUserLogQueryResponse> => {
    return request({
        url: 'manager/userLog/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [用户日志模块] 新增接口
export const userLogCreateService = (formData: IUserLogCreateRequest): Promise<IUserLogResponse> => {
    return request({
        url: 'manager/userLog/create',
        method: 'post',
        data: formData
    })
}

// [用户日志模块] 获取详情接口
export const userLogShowService = (id: number): Promise<IUserLogResponse> => {
    return request({
        url: 'manager/userLog/show',
        method: 'get',
        params: {id}
    })
}

// [用户日志模块] 更新接口
export const userLogUpdateService = (formData: IUserLogUpdateRequest): Promise<IUserLogResponse> => {
    return request({
        url: 'manager/userLog/update',
        method: 'put',
        data: formData
    })
}

// [用户日志模块] 删除接口
export const userLogDestroyService = (id: number): Promise<IUserLogDestroyResponse> => {
    return request({
        url: 'manager/userLog/destroy',
        method: 'delete',
        params: {id}
    })
}

// [用户资源权限模块] 查询列表接口
export const userPermissionQueryService = (page: number, pageSize: number, formData: IUserPermissionQueryRequest): Promise<IUserPermissionQueryResponse> => {
    return request({
        url: 'manager/userPermission/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [用户资源权限模块] 新增接口
export const userPermissionCreateService = (formData: IUserPermissionCreateRequest): Promise<IUserPermissionResponse> => {
    return request({
        url: 'manager/userPermission/create',
        method: 'post',
        data: formData
    })
}

// [用户资源权限模块] 获取详情接口
export const userPermissionShowService = (id: number): Promise<IUserPermissionResponse> => {
    return request({
        url: 'manager/userPermission/show',
        method: 'get',
        params: {id}
    })
}

// [用户资源权限模块] 更新接口
export const userPermissionUpdateService = (formData: IUserPermissionUpdateRequest): Promise<IUserPermissionResponse> => {
    return request({
        url: 'manager/userPermission/update',
        method: 'put',
        data: formData
    })
}

// [用户资源权限模块] 删除接口
export const userPermissionDestroyService = (id: number): Promise<IUserPermissionDestroyResponse> => {
    return request({
        url: 'manager/userPermission/destroy',
        method: 'delete',
        params: {id}
    })
}

// [用户角色模块] 查询列表接口
export const userRoleQueryService = (page: number, pageSize: number, formData: IUserRoleQueryRequest): Promise<IUserRoleQueryResponse> => {
    return request({
        url: 'manager/userRole/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [用户角色模块] 新增接口
export const userRoleCreateService = (formData: IUserRoleCreateRequest): Promise<IUserRoleResponse> => {
    return request({
        url: 'manager/userRole/create',
        method: 'post',
        data: formData
    })
}

// [用户角色模块] 获取详情接口
export const userRoleShowService = (id: number): Promise<IUserRoleResponse> => {
    return request({
        url: 'manager/userRole/show',
        method: 'get',
        params: {id}
    })
}

// [用户角色模块] 更新接口
export const userRoleUpdateService = (formData: IUserRoleUpdateRequest): Promise<IUserRoleResponse> => {
    return request({
        url: 'manager/userRole/update',
        method: 'put',
        data: formData
    })
}

// [用户角色模块] 删除接口
export const userRoleDestroyService = (id: number): Promise<IUserRoleDestroyResponse> => {
    return request({
        url: 'manager/userRole/destroy',
        method: 'delete',
        params: {id}
    })
}

// [权限管理] 权限列表
export const permissionService = (): Promise<any> => {
    return request({
        url: 'manager/permission',
        method: 'get'
    })
}

// [角色管理] 角色列表
export const roleService = (): Promise<any> => {
    return request({
        url: 'manager/role',
        method: 'get'
    })
}

// [买家收货地址] 买家收货地址
export const userAddressService = (userId: number, page: number, pageSize: number): Promise<IAddressResponse> => {
    return request({
        url: 'manager/userAddress',
        method: 'get',
        params: {userId, page, pageSize}
    })
}

// [用户管理] 用户列表
export const userService = (): Promise<any> => {
    return request({
        url: 'manager/user',
        method: 'get'
    })
}

// [用户管理] 添加新用户
export const userStoreService = (): Promise<any> => {
    return request({
        url: 'manager/user/store',
        method: 'post'
    })
}

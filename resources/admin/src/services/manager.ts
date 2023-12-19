import request from '@/utils/request'
import type {  } from '@/types/manager'

// [管理员] 管理员接口
export const apiManagerAdminService = (): Promise<any> => {
    return request({
        url: '/api/manager/admin',
        method: 'get'
    })
}

// [运营] 运营首页
export const apiManagerDashboardService = (): Promise<any> => {
    return request({
        url: '/api/manager/dashboard',
        method: 'get'
    })
}

// [运营中心] 管理仪表台
export const apiManagerIndex1Service = (): Promise<any> => {
    return request({
        url: '/api/manager/index1',
        method: 'get'
    })
}

// [权限管理] 权限列表
export const apiManagerPermissionService = (): Promise<any> => {
    return request({
        url: '/api/manager/permission',
        method: 'get'
    })
}

// [角色管理] 角色列表
export const apiManagerRoleService = (): Promise<any> => {
    return request({
        url: '/api/manager/role',
        method: 'get'
    })
}

// [卖家管理] 全部卖家
export const apiManagerSellerService = (): Promise<any> => {
    return request({
        url: '/api/manager/seller',
        method: 'get'
    })
}

// [店铺管理] 卖家店铺
export const apiManagerShopService = (): Promise<any> => {
    return request({
        url: '/api/manager/shop',
        method: 'get'
    })
}

// [门店管理] 卖家门店
export const apiManagerStoreService = (): Promise<any> => {
    return request({
        url: '/api/manager/store',
        method: 'get'
    })
}

// [买家收货地址] 买家收货地址
export const apiManagerUserAddressService = (): Promise<any> => {
    return request({
        url: '/api/manager/userAddress',
        method: 'get'
    })
}

// [用户管理] 用户列表
export const apiManagerUserService = (): Promise<any> => {
    return request({
        url: '/api/manager/user',
        method: 'get'
    })
}

// [用户管理] 添加新用户
export const apiManagerUserStoreService = (): Promise<any> => {
    return request({
        url: '/api/manager/user/store',
        method: 'post'
    })
}

// [用户管理] 获取详情
export const apiManagerUserShowService = (): Promise<any> => {
    return request({
        url: '/api/manager/user/show',
        method: 'get'
    })
}

// [用户管理] 更新用户详情
export const apiManagerUserUpdateService = (): Promise<any> => {
    return request({
        url: '/api/manager/user/update',
        method: 'put'
    })
}

// [用户管理] 删除用户
export const apiManagerUserDestroyService = (): Promise<any> => {
    return request({
        url: '/api/manager/user/destroy',
        method: 'delete'
    })
}

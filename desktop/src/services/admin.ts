import request from '@/utils/request'
import type {  } from '@/types/admin'

// [运营] 运营首页
export const adminDashboardService = (): Promise<any> => {
    return request({
        url: '/admin/dashboard',
        method: 'get'
    })
}

// [运营中心] 仪表台
export const adminService = (): Promise<any> => {
    return request({
        url: '/admin',
        method: 'get'
    })
}

// [员工管理] 运营员工管理
export const adminManagerService = (): Promise<any> => {
    return request({
        url: '/admin/manager',
        method: 'get'
    })
}

// [权限管理] 权限列表
export const adminPermissionService = (): Promise<any> => {
    return request({
        url: '/admin/permission',
        method: 'get'
    })
}

// [角色管理] 角色列表
export const adminRoleService = (): Promise<any> => {
    return request({
        url: '/admin/role',
        method: 'get'
    })
}

// [卖家管理] 全部卖家
export const adminSellerService = (): Promise<any> => {
    return request({
        url: '/admin/seller',
        method: 'get'
    })
}

// [店铺管理] 卖家店铺
export const adminShopService = (): Promise<any> => {
    return request({
        url: '/admin/shop',
        method: 'get'
    })
}

// [门店管理] 卖家门店
export const adminStoreService = (): Promise<any> => {
    return request({
        url: '/admin/store',
        method: 'get'
    })
}

// [买家收货地址] 买家收货地址
export const adminUserAddressService = (): Promise<any> => {
    return request({
        url: '/admin/userAddress',
        method: 'get'
    })
}

// [买家管理] 买家管理
export const adminUserService = (): Promise<any> => {
    return request({
        url: '/admin/user',
        method: 'get'
    })
}

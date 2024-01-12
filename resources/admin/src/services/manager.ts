import request from '@/utils/request'
import type {  } from '@/types/manager'

// [管理员] 管理员接口
export const adminService = (): Promise<any> => {
    return request({
        url: 'manager/admin',
        method: 'get'
    })
}

// [运营] 运营首页
export const dashboardService = (): Promise<any> => {
    return request({
        url: 'manager/dashboard',
        method: 'get'
    })
}

// [运营中心] 管理仪表台
export const adminIndex1Service = (): Promise<any> => {
    return request({
        url: 'manager/admin/index1',
        method: 'get'
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

// [卖家管理] 全部卖家
export const sellerService = (): Promise<any> => {
    return request({
        url: 'manager/seller',
        method: 'get'
    })
}

// [店铺管理] 卖家店铺
export const shopService = (): Promise<any> => {
    return request({
        url: 'manager/shop',
        method: 'get'
    })
}

// [门店管理] 卖家门店
export const storeService = (): Promise<any> => {
    return request({
        url: 'manager/store',
        method: 'get'
    })
}

// [买家收货地址] 买家收货地址
export const userAddressService = (): Promise<any> => {
    return request({
        url: 'manager/userAddress',
        method: 'get'
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

// [用户管理] 获取详情
export const userShowService = (): Promise<any> => {
    return request({
        url: 'manager/user/show',
        method: 'get'
    })
}

// [用户管理] 更新用户详情
export const userUpdateService = (): Promise<any> => {
    return request({
        url: 'manager/user/update',
        method: 'put'
    })
}

// [用户管理] 删除用户
export const userDestroyService = (): Promise<any> => {
    return request({
        url: 'manager/user/destroy',
        method: 'delete'
    })
}

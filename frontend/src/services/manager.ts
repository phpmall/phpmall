import request from '@/utils/request'
import type { IAddressResponse } from '@/types/manager'

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

// [运营中心] 获取个人资料
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
export const userAddressService = (
  userId: number,
  page: number,
  pageSize: number
): Promise<IAddressResponse> => {
  return request({
    url: 'manager/userAddress',
    method: 'get',
    params: { userId, page, pageSize }
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

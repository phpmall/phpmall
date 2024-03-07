import request from '@/utils/request'
import type {  } from '@/types/user.d'

// [用户中心] 仪表台
export const dashboardService = (): Promise<any> => {
    return request({
        url: 'user/dashboard',
        method: 'get'
    })
}

// [素材] 附件上传接口
export const uploadService = (): PromiseApp\Bundles\Material\Responses\UploadResponse => {
    return request({
        url: 'user/upload',
        method: 'post'
    })
}

// [收货地址] 获取用户全部收货地址
export const addressService = (page: number, pageSize: number): PromiseApp\Bundles\User\Responses\AddressResponse => {
    return request({
        url: 'user/address',
        method: 'get',
        params: {page, pageSize}
    })
}

// [收货地址] 新增用户收货地址
export const addressStoreService = (): Promise<any> => {
    return request({
        url: 'user/address/store',
        method: 'post'
    })
}

// [收货地址] 查询用户收货地址
export const addressShowService = (id: number): PromiseApp\Bundles\User\Responses\AddressResponse => {
    return request({
        url: 'user/address/show',
        method: 'get',
        params: {id}
    })
}

// [收货地址] 更新用户收货地址
export const addressUpdateService = (): Promise<any> => {
    return request({
        url: 'user/address/update',
        method: 'put'
    })
}

// [收货地址] 删除用户收货地址
export const addressDestroyService = (id: number): Promise<any> => {
    return request({
        url: 'user/address/destroy',
        method: 'delete',
        params: {id}
    })
}

// [用户中心] 获取用户资料
export const profileShowService = (): PromiseApp\Bundles\User\Responses\ProfileResponse => {
    return request({
        url: 'user/profile/show',
        method: 'get'
    })
}

// [用户中心] 更新用户资料
export const profileUpdateService = (): Promise<any> => {
    return request({
        url: 'user/profile/update',
        method: 'put'
    })
}

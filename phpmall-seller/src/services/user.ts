import request from '@/utils/request'
import type { IAddressQueryRequest,
IAddressResponse,
IAddressCreateRequest,
IAddressUpdateRequest,
IProfileResponse } from '@/types/user'

// [用户中心] 仪表台
export const dashboardService = (): Promise<any> => {
    return request({
        url: 'user/dashboard',
        method: 'get'
    })
}

// [收货地址] 获取用户全部收货地址
export const addressService = (page: number, pageSize: number, formData: IAddressQueryRequest): Promise<IAddressResponse> => {
    return request({
        url: 'user/address',
        method: 'get',
        params: {page, pageSize},
        data: formData
    })
}

// [收货地址] 新增用户收货地址
export const addressStoreService = (formData: IAddressCreateRequest): Promise<any> => {
    return request({
        url: 'user/address/store',
        method: 'post',
        data: formData
    })
}

// [收货地址] 查询用户收货地址
export const addressShowService = (id: number): Promise<IAddressResponse> => {
    return request({
        url: 'user/address/show',
        method: 'get',
        params: {id}
    })
}

// [收货地址] 更新用户收货地址
export const addressUpdateService = (formData: IAddressUpdateRequest): Promise<any> => {
    return request({
        url: 'user/address/update',
        method: 'put',
        data: formData
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
export const profileShowService = (): Promise<IProfileResponse> => {
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

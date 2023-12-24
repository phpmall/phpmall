import request from '@/utils/request'
import type { IAddressQueryRequest,
IAddressResponse,
IAddressCreateRequest,
IAddressUpdateRequest } from '@/types/user'

// [用户中心] 仪表台
export const userService = (): Promise<any> => {
    return request({
        url: '/user',
        method: 'get'
    })
}

// [收货地址] 获取用户全部收货地址
export const addressService = (page: number, pageSize: number, formData: IAddressQueryRequest): Promise<IAddressResponse> => {
    return request({
        url: '/address',
        method: 'get',
        params: {page, pageSize},
        data: formData
    })
}

// [收货地址] 新增用户收货地址
export const addressStoreService = (formData: IAddressCreateRequest): Promise<any> => {
    return request({
        url: '/address/store',
        method: 'post',
        data: formData
    })
}

// [收货地址] 查询用户收货地址
export const addressShowService = (id: number): Promise<IAddressResponse> => {
    return request({
        url: '/address/show',
        method: 'get',
        params: {id}
    })
}

// [收货地址] 更新用户收货地址
export const addressUpdateService = (formData: IAddressUpdateRequest): Promise<any> => {
    return request({
        url: '/address/update',
        method: 'put',
        data: formData
    })
}

// [收货地址] 删除用户收货地址
export const addressDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/address/destroy',
        method: 'delete',
        params: {id}
    })
}

// [用户中心] 获取用户资料
export const profileShowService = (): PromiseApp\Api\User\Responses\ProfileResponse => {
    return request({
        url: '/profile/show',
        method: 'get'
    })
}

// [用户中心] 更新用户资料
export const profileUpdateService = (): Promise<any> => {
    return request({
        url: '/profile/update',
        method: 'put'
    })
}

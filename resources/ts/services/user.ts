import request from '@/utils/request'
import type { IAddressQueryRequest,
IAddressResponse,
IAddressUpdateRequest,
IAddressCreateRequest,
ILoginRequest,
ILoginResponse } from '@/types/user'

// [收货地址] 获取用户全部收货地址
export const userAddressService = (page: number, pageSize: number, formData: IAddressQueryRequest): Promise<IAddressResponse> => {
    return request({
        url: '/user/address',
        method: 'get',
        params: {page, pageSize},
        data: formData
    })
}

// [收货地址] 更新用户收货地址
export const userAddressService = (page: number, pageSize: number, formData: IAddressQueryRequest, formData: IAddressUpdateRequest): Promise<any> => {
    return request({
        url: '/user/address',
        method: 'put',
        params: {page, pageSize},
        data: formData,
        data: formData
    })
}

// [收货地址] 新增用户收货地址
export const userAddressService = (page: number, pageSize: number, formData: IAddressQueryRequest, formData: IAddressUpdateRequest, formData: IAddressCreateRequest): Promise<any> => {
    return request({
        url: '/user/address',
        method: 'post',
        params: {page, pageSize},
        data: formData,
        data: formData,
        data: formData
    })
}

// [收货地址] 删除用户收货地址
export const userAddressService = (id: number): Promise<any> => {
    return request({
        url: '/user/address',
        method: 'delete',
        params: {id}
    })
}

// [收货地址] 查询用户收货地址
export const userAddressShowService = (id: number): Promise<IAddressResponse> => {
    return request({
        url: '/user/address/show',
        method: 'get',
        params: {id}
    })
}

// [认证管理] 登录操作
export const userLoginService = (formData: ILoginRequest): Promise<ILoginResponse> => {
    return request({
        url: '/user/login',
        method: 'post',
        data: formData
    })
}

// [用户中心] 仪表台
export const userService = (): Promise<any> => {
    return request({
        url: '/user',
        method: 'get'
    })
}

// [用户资料] 获取用户详细信息
export const userProfileService = (): Promise<any> => {
    return request({
        url: '/user/profile',
        method: 'get'
    })
}

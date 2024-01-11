import request from '@/utils/request'
import type { IForgetMobileRequest,
ILoginRequest,
ILoginResponse,
ILoginMobileRequest,
ILoginSmsRequest,
IResetRequest,
ISignupMobileRequest } from '@/types/auth'

// [忘记密码] 发送手机短信验证码
export const apiAuthForgetMobileService = (formData: IForgetMobileRequest): Promise<any> => {
    return request({
        url: '/api/auth/forget/mobile',
        method: 'post',
        data: formData
    })
}

// [认证管理] 通过手机号和密码登录
export const apiAuthLoginMobileService = (formData: ILoginRequest): Promise<ILoginResponse> => {
    return request({
        url: '/api/auth/login/mobile',
        method: 'post',
        data: formData
    })
}

// [登录] 通过手机号和密码登录
export const loginMobile2Service = (formData: ILoginMobileRequest): Promise<ILoginResponse> => {
    return request({
        url: '/login/mobile2',
        method: 'post',
        data: formData
    })
}

// [登录] 通过手机短信验证码登录
export const loginMobileService = (formData: ILoginSmsRequest): Promise<ILoginResponse> => {
    return request({
        url: '/login/mobile',
        method: 'post',
        data: formData
    })
}

// [重设密码] 通过验证码重新设置新密码
export const apiAuthResetService = (formData: IResetRequest): Promise<any> => {
    return request({
        url: '/api/auth/reset',
        method: 'post',
        data: formData
    })
}

// [注册] 通过手机号码注册
export const apiAuthSignupMobileService = (formData: ISignupMobileRequest): Promise<any> => {
    return request({
        url: '/api/auth/signup/mobile',
        method: 'post',
        data: formData
    })
}

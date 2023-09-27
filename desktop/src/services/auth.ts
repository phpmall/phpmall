import request from '@/utils/request'
import type { IForgetMobileRequest,
ILoginRequest,
ILoginResponse,
ILoginMobileRequest,
ILoginSmsRequest,
IResetRequest,
ISignupMobileRequest } from '@/types/auth'

// [忘记密码] 发送手机短信验证码
export const forgetMobileService = (formData: IForgetMobileRequest): Promise<any> => {
    return request({
        url: '/forget/mobile',
        method: 'post',
        data: formData
    })
}

// [认证管理] 通过手机号和密码登录
export const authLoginMobileService = (formData: ILoginRequest): Promise<ILoginResponse> => {
    return request({
        url: '/auth/login/mobile',
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

// [授权登录] 获取授权跳转地址
export const oauthRedirectService = (): Promise<any> => {
    return request({
        url: '/oauth/redirect',
        method: 'post'
    })
}

// [授权登录] 授权登录回调地址
export const oauthCallbackService = (): Promise<any> => {
    return request({
        url: '/oauth/callback',
        method: 'post'
    })
}

// [授权登录] 新用户绑定接口
export const oauthBindService = (): Promise<any> => {
    return request({
        url: '/oauth/bind',
        method: 'post'
    })
}

// [重设密码] 通过验证码重新设置新密码
export const resetService = (formData: IResetRequest): Promise<any> => {
    return request({
        url: '/reset',
        method: 'post',
        data: formData
    })
}

// [注册] 通过手机号码注册
export const signupMobileService = (formData: ISignupMobileRequest): Promise<any> => {
    return request({
        url: '/signup/mobile',
        method: 'post',
        data: formData
    })
}

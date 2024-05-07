import request from '@/utils/request'
import type { IForgetMobileRequest,
ILoginRequest,
ILoginResponse,
ILoginSmsRequest,
IResetRequest,
ISignupMobileRequest,
ICaptchaResponse,
ISmsSendRequest } from '@/types/common.d'

// [忘记密码] 发送手机短信验证码
export const forgetMobileService = (formData: IForgetMobileRequest): Promise<any> => {
    return request({
        url: 'common/forget/mobile',
        method: 'post',
        data: formData
    })
}

// [登录] 通过用户名和密码登录
export const loginService = (formData: ILoginRequest): Promise<ILoginResponse> => {
    return request({
        url: 'common/login',
        method: 'post',
        data: formData
    })
}

// [认证管理] 通过手机号和密码登录
export const loginMobileService = (formData: ILoginRequest): Promise<ILoginResponse> => {
    return request({
        url: 'common/login/mobile',
        method: 'post',
        data: formData
    })
}

// [登录] 通过手机短信验证码登录
export const loginSmsCodeService = (formData: ILoginSmsRequest): Promise<ILoginResponse> => {
    return request({
        url: 'common/login/smsCode',
        method: 'post',
        data: formData
    })
}

// [开放授权登录] 获取授权跳转地址
export const oauthRedirectService = (): Promise<any> => {
    return request({
        url: 'common/oauth/redirect',
        method: 'post'
    })
}

// [开放授权登录] 授权登录回调地址
export const oauthCallbackService = (): Promise<any> => {
    return request({
        url: 'common/oauth/callback',
        method: 'post'
    })
}

// [开放授权登录] 新用户绑定接口
export const oauthBindService = (): Promise<any> => {
    return request({
        url: 'common/oauth/bind',
        method: 'post'
    })
}

// [重设密码] 通过验证码重新设置新密码
export const resetService = (formData: IResetRequest): Promise<any> => {
    return request({
        url: 'common/reset',
        method: 'post',
        data: formData
    })
}

// [注册] 通过手机号码注册
export const signupMobileService = (formData: ISignupMobileRequest): Promise<any> => {
    return request({
        url: 'common/signup/mobile',
        method: 'post',
        data: formData
    })
}

// [验证码] 图片验证码
export const captchaService = (): Promise<ICaptchaResponse> => {
    return request({
        url: 'common/captcha',
        method: 'get'
    })
}

// [地区] 查询地区列表
export const regionService = (id: number): PromiseApp\Bundles\Region\Responses\Common\RegionResponse => {
    return request({
        url: 'common/region',
        method: 'get',
        params: {id}
    })
}

// [短信] 发送手机短信验证码
export const smsService = (formData: ISmsSendRequest): Promise<any> => {
    return request({
        url: 'common/sms',
        method: 'post',
        data: formData
    })
}

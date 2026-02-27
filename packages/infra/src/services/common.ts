import request from '@/utils/request'
import type { ICaptchaImageResponse } from '@/types/common.d'

// [公共模块] 图片验证码接口
export const captchaImageService = (): Promise<ICaptchaImageResponse> => {
    return request({
        url: '/captcha/image',
        method: 'get'
    })
}

// [公共模块] 发送短信验证码接口
export const smsSendService = (): Promise<any> => {
    return request({
        url: '/sms/send',
        method: 'post'
    })
}

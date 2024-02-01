import request from '@/utils/request'
import type { ICaptchaResponse,
IRegionResponse,
ISmsSendRequest } from '@/types/common.d'

// [验证码] 图片验证码
export const captchaService = (): Promise<ICaptchaResponse> => {
    return request({
        url: 'common/captcha',
        method: 'get'
    })
}

// [地区] 查询地区列表
export const regionService = (id: number): Promise<IRegionResponse> => {
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

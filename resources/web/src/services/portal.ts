import request from '@/utils/request'
import type {  } from '@/types/portal.d'

// [验证码] 图片验证码
export const captchaService = (): PromiseApp\Bundles\Captcha\Responses\CaptchaResponse => {
    return request({
        url: 'portal/captcha',
        method: 'get'
    })
}

// [地区] 查询地区列表
export const regionService = (id: number): PromiseApp\Bundles\Region\Responses\RegionResponse => {
    return request({
        url: 'portal/region',
        method: 'get',
        params: {id}
    })
}

// [短信] 发送手机短信验证码
export const smsService = (): Promise<any> => {
    return request({
        url: 'portal/sms',
        method: 'post'
    })
}

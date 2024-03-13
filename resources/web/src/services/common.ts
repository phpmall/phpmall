import request from '@/utils/request'
import type {  } from '@/types/common.d'

// [验证码] 图片验证码
export const captchaService = (): PromiseApp\Bundles\Captcha\Responses\CaptchaResponse => {
    return request({
        url: 'common/captcha',
        method: 'get'
    })
}

// [地区] 查询地区列表
export const regionService = (id: number): PromiseApp\Bundles\Region\Responses\RegionResponse => {
    return request({
        url: 'common/region',
        method: 'get',
        params: {id}
    })
}

// [短信] 发送手机短信验证码
export const smsService = (): Promise<any> => {
    return request({
        url: 'common/sms',
        method: 'post'
    })
}

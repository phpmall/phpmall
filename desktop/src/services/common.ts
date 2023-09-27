import request from '@/utils/request'
import type { ICaptchaResponse } from '@/types/common'

// [验证码] 图片验证码
export const commonCaptchaService = (): Promise<ICaptchaResponse> => {
    return request({
        url: '/common/captcha',
        method: 'get'
    })
}

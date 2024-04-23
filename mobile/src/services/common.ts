import request from '@/utils/request'
import type { ICaptchaResponse } from '@/types/common.d'

// [验证码] 图片验证码
export const captchaService = (): Promise<ICaptchaResponse> => {
    return request({
        url: 'common/captcha',
        method: 'get'
    })
}

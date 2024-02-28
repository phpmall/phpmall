import request from '@/utils/request'
import type { ICaptchaResponse, IRegionResponse, ISmsSendRequest } from '@/types/portal.d'

// [验证码] 图片验证码
export const captchaService = (): Promise<ICaptchaResponse> => {
  return request({
    url: 'portal/captcha',
    method: 'get'
  })
}

// [地区] 查询地区列表
export const regionService = (id: number): Promise<IRegionResponse> => {
  return request({
    url: 'portal/region',
    method: 'get',
    params: { id }
  })
}

// [短信] 发送手机短信验证码
export const smsService = (formData: ISmsSendRequest): Promise<any> => {
  return request({
    url: 'portal/sms',
    method: 'post',
    data: formData
  })
}

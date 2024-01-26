import request from '@/utils/request'
import type { ICaptchaResponse,
IUploadRequest,
IUploadResponse,
IRegionResponse,
ISmsSendRequest } from '@/types/portal'

// [验证码] 图片验证码
export const captchaService = (): Promise<ICaptchaResponse> => {
    return request({
        url: 'portal/captcha',
        method: 'get'
    })
}

// [素材] 附件上传接口
export const uploadService = (formData: IUploadRequest): Promise<IUploadResponse> => {
    return request({
        url: 'portal/upload',
        method: 'post',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
    })
}

// [地区] 查询地区列表
export const regionService = (id: number): Promise<IRegionResponse> => {
    return request({
        url: 'portal/region',
        method: 'get',
        params: {id}
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

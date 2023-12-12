import request from '@/utils/request'
import type { IUploadRequest,
IUploadResponse,
ICaptchaResponse,
IRegionResponse,
ISmsSendRequest } from '@/types/common'

// [素材] 附件上传接口
export const commonUploadService = (formData: IUploadRequest): Promise<IUploadResponse> => {
    return request({
        url: '/common/upload',
        method: 'post',
        data: formData,
        headers: { 'Content-Type': 'multipart/form-data' }
    })
}

// [验证码] 图片验证码
export const commonCaptchaService = (): Promise<ICaptchaResponse> => {
    return request({
        url: '/common/captcha',
        method: 'get'
    })
}

// [地区] 查询地区列表
export const commonRegionService = (id: number): Promise<IRegionResponse> => {
    return request({
        url: '/common/region',
        method: 'get',
        params: {id}
    })
}

// [短信] 发送手机短信验证码
export const commonSmsService = (formData: ISmsSendRequest): Promise<any> => {
    return request({
        url: '/common/sms',
        method: 'post',
        data: formData
    })
}

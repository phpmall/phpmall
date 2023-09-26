import request from '@/utils/request'
import type { ILoginRequest,
ILoginResponse } from '@/types/admin'

// [认证管理] 登录操作
export const adminAuthLoginService = (formData: ILoginRequest): Promise<ILoginResponse> => {
    return request({
        url: '/admin/auth/login',
        method: 'post',
        data: formData
    })
}

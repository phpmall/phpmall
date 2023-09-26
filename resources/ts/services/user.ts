import request from '@/utils/request'
import type { ILoginRequest,
ILoginResponse } from '@/types/user'

// [认证管理] 登录操作
export const userAuthLoginService = (formData: ILoginRequest): Promise<ILoginResponse> => {
    return request({
        url: '/user/auth/login',
        method: 'post',
        data: formData
    })
}

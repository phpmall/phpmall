import request from '@/utils/request'
import type { IProfileResponse, IProfileRequest } from '@/types/user'

// [用户中心] 用户仪表台
export const apiUserService = (): Promise<any> => {
  return request({
    url: '/user',
    method: 'get'
  })
}

// [用户中心] 获取用户资料
export const apiUserProfileShowService = (): Promise<IProfileResponse> => {
  return request({
    url: '/user/profile/show',
    method: 'get'
  })
}

// [用户中心] 更新用户资料
export const apiUserProfileUpdateService = (formData: IProfileRequest): Promise<any> => {
  return request({
    url: '/user/profile/update',
    method: 'put',
    data: formData
  })
}

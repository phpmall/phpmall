import request from '@/utils/request'
import type {  } from '@/types/user'

// [用户中心] 仪表台
export const userService = (): Promise<any> => {
    return request({
        url: '/user',
        method: 'get'
    })
}

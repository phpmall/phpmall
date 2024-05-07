import request from '@/utils/request'
import type {  } from '@/types/seller.d'

// [商家中心] 商家首页
export const dashboardService = (): Promise<any> => {
    return request({
        url: 'seller/dashboard',
        method: 'get'
    })
}

// [商家中心] 获取管理菜单
export const dashboardMenuService = (): Promise<any> => {
    return request({
        url: 'seller/dashboard/menu',
        method: 'get'
    })
}

// [商家中心] 获取系统消息
export const dashboardMessageService = (): Promise<any> => {
    return request({
        url: 'seller/dashboard/message',
        method: 'get'
    })
}

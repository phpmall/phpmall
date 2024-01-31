import request from '@/utils/request'
import type {} from '@/types/seller'

// [seller] 卖家
export const dashboardService = (): Promise<any> => {
  return request({
    url: 'seller/dashboard',
    method: 'get'
  })
}

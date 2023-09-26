import request from '@/utils/request'
import type {  } from '@/types/seller'

// [seller] 卖家
export const sellerService = (): Promise<any> => {
    return request({
        url: '/seller',
        method: 'get'
    })
}

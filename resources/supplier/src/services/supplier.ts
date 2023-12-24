import request from '@/utils/request'
import type {  } from '@/types/supplier'

// [supplier] supplier
export const supplierService = (): Promise<any> => {
    return request({
        url: '/supplier',
        method: 'get'
    })
}

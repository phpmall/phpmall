import request from '@/utils/request'
import type {  } from '@/types/portal'

// [类目] 全部类目
export const portalCatalogService = (): Promise<any> => {
    return request({
        url: '/portal/catalog',
        method: 'get'
    })
}

// [商品分类] 商品分类
export const portalCategoryService = (): Promise<any> => {
    return request({
        url: '/portal/category',
        method: 'get'
    })
}

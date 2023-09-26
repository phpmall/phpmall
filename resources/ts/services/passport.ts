import request from '@/utils/request'

export function captchaService() {
    return request({
        url: '/',
        method: 'get'
    })
}
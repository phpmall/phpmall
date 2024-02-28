import Cookies from 'js-cookie'
import { tokenKey } from '@/stores/auth'

export const Local = {
  // 设置永久缓存
  set(key: string, val: any) {
    window.localStorage.setItem(key, JSON.stringify(val))
  },
  // 获取永久缓存
  get(key: string) {
    const json: any = window.localStorage.getItem(key)
    return JSON.parse(json)
  },
  // 移除永久缓存
  remove(key: string) {
    window.localStorage.removeItem(key)
  },
  // 移除全部永久缓存
  clear() {
    window.localStorage.clear()
  }
}

export const Session = {
  // 设置临时缓存
  set(key: string, val: any) {
    if (key === tokenKey) return Cookies.set(key, val)
    window.sessionStorage.setItem(key, JSON.stringify(val))
  },
  // 获取临时缓存
  get(key: string) {
    if (key === tokenKey) return Cookies.get(key)
    const json: any = window.sessionStorage.getItem(key)
    return JSON.parse(json)
  },
  // 移除临时缓存
  remove(key: string) {
    if (key === tokenKey) return Cookies.remove(key)
    window.sessionStorage.removeItem(key)
  },
  // 移除全部临时缓存
  clear() {
    Cookies.remove(tokenKey)
    window.sessionStorage.clear()
  }
}

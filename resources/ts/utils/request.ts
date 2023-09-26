import axios from 'axios'
import { ElMessage, ElMessageBox } from 'element-plus'
import { Session } from '@/utils/storage'

// 配置新建一个 axios 实例
const httpClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL as string,
  timeout: 5000,
  headers: { 'Content-Type': 'application/json' }
})

// 添加请求拦截器
httpClient.interceptors.request.use(
  (config) => {
    // 在发送请求之前做些什么 token
    if (Session.get('token')) {
      ;(<any>config.headers).common['Authorization'] = `Bearer ${Session.get('token')}`
    }
    return config
  },
  (error) => {
    // 对请求错误做些什么
    return Promise.reject(error)
  }
)

// 添加响应拦截器
httpClient.interceptors.response.use(
  (response) => {
    // 对响应数据做点什么
    const { code, message, data } = response.data
    if (code === 401) {
      ElMessageBox.alert('登录状态已过期，请重新登录', '提示', { confirmButtonText: '确定' })
        .then(() => {
          Session.clear() // 清除浏览器全部临时缓存
          window.location.href = '/' // 去登录页
        })
        .catch(() => {})
    } else if (code !== 0) {
      ElMessage.error(message)
      return Promise.reject(new Error(message))
    } else {
      return data
    }
  },
  (error) => {
    // 对响应错误做点什么
    if (error.message.indexOf('timeout') != -1) {
      ElMessage.error('网络超时')
    } else if (error.message == 'Network Error') {
      ElMessage.error('网络连接错误')
    } else {
      if (error.response.data) ElMessage.error(error.response.statusText)
      else ElMessage.error('接口路径找不到')
    }
    return Promise.reject(error)
  }
)

export default httpClient

import axios, { type AxiosInstance, type AxiosRequestConfig, type AxiosResponse, type InternalAxiosRequestConfig } from 'axios'

// 响应数据接口
interface ResponseData<T = any> {
  code: number
  message: string
  data: T
}

// 请求配置扩展
interface RequestConfig extends AxiosRequestConfig {
  skipErrorHandler?: boolean // 跳过错误处理
  skipAuth?: boolean // 跳过认证
}

// 创建 axios 实例
const service: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api/admin',
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json;charset=UTF-8'
  }
})

// 请求拦截器
service.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    const customConfig = config as InternalAxiosRequestConfig & RequestConfig

    // 添加认证 Token
    if (!customConfig.skipAuth) {
      const token = getToken()
      if (token) {
        config.headers.Authorization = `Bearer ${token}`
      }
    }

    // 添加时间戳防止缓存
    if (config.method === 'get') {
      config.params = {
        ...config.params,
        _t: Date.now()
      }
    }

    return config
  },
  (error) => {
    console.error('Request error:', error)
    return Promise.reject(error)
  }
)

// 响应拦截器
service.interceptors.response.use(
  (response: AxiosResponse<ResponseData>) => {
    const { data, config } = response
    const customConfig = config as RequestConfig

    // 二进制数据直接返回
    if (response.config.responseType === 'blob') {
      return response
    }

    // 处理业务错误
    if (data.code !== undefined && data.code !== 200 && data.code !== 0) {
      // 跳过错误处理
      if (customConfig.skipErrorHandler) {
        return Promise.reject(data)
      }

      // 401 未授权
      if (data.code === 401) {
        handleUnauthorized()
        return Promise.reject(new Error(data.message || '未授权，请重新登录'))
      }

      // 403 禁止访问
      if (data.code === 403) {
        console.error('Access denied:', data.message)
        return Promise.reject(new Error(data.message || '无权限访问'))
      }

      // 其他业务错误
      console.error('Business error:', data.message)
      return Promise.reject(new Error(data.message || '请求失败'))
    }

    // 返回数据
    return data.data !== undefined ? data.data : data
  },
  (error) => {
    const { response, config } = error
    const customConfig = config as RequestConfig

    // 跳过错误处理
    if (customConfig?.skipErrorHandler) {
      return Promise.reject(error)
    }

    // 处理 HTTP 错误
    if (response) {
      const { status, data } = response
      let message = data?.message || error.message

      switch (status) {
        case 400:
          message = message || '请求参数错误'
          break
        case 401:
          message = message || '未授权，请重新登录'
          handleUnauthorized()
          break
        case 403:
          message = message || '无权限访问'
          break
        case 404:
          message = message || '请求资源不存在'
          break
        case 405:
          message = message || '请求方法不允许'
          break
        case 408:
          message = message || '请求超时'
          break
        case 500:
          message = message || '服务器内部错误'
          break
        case 501:
          message = message || '服务未实现'
          break
        case 502:
          message = message || '网关错误'
          break
        case 503:
          message = message || '服务不可用'
          break
        case 504:
          message = message || '网关超时'
          break
        default:
          message = message || `请求错误(${status})`
      }

      console.error('HTTP error:', message)
      return Promise.reject(new Error(message))
    }

    // 网络错误
    if (error.message?.includes('Network Error')) {
      console.error('Network error')
      return Promise.reject(new Error('网络连接失败，请检查网络'))
    }

    // 超时错误
    if (error.code === 'ECONNABORTED' || error.message?.includes('timeout')) {
      console.error('Request timeout')
      return Promise.reject(new Error('请求超时，请稍后重试'))
    }

    // 请求取消
    if (axios.isCancel(error)) {
      console.log('Request canceled:', error.message)
      return Promise.reject(error)
    }

    console.error('Unknown error:', error)
    return Promise.reject(error)
  }
)

// 获取 Token
function getToken(): string {
  return localStorage.getItem('token') || sessionStorage.getItem('token') || ''
}

// 处理未授权
function handleUnauthorized(): void {
  // 清除 Token
  localStorage.removeItem('token')
  sessionStorage.removeItem('token')

  // 跳转到登录页
  const loginPath = '/login'
  if (window.location.pathname !== loginPath) {
    window.location.href = loginPath
  }
}

// 请求方法封装
const request = {
  /**
   * GET 请求
   */
  get<T = any>(url: string, params?: any, config?: RequestConfig): Promise<T> {
    return service.get(url, { params, ...config })
  },

  /**
   * POST 请求
   */
  post<T = any>(url: string, data?: any, config?: RequestConfig): Promise<T> {
    return service.post(url, data, config)
  },

  /**
   * PUT 请求
   */
  put<T = any>(url: string, data?: any, config?: RequestConfig): Promise<T> {
    return service.put(url, data, config)
  },

  /**
   * DELETE 请求
   */
  delete<T = any>(url: string, params?: any, config?: RequestConfig): Promise<T> {
    return service.delete(url, { params, ...config })
  },

  /**
   * PATCH 请求
   */
  patch<T = any>(url: string, data?: any, config?: RequestConfig): Promise<T> {
    return service.patch(url, data, config)
  },

  /**
   * 上传文件
   */
  upload<T = any>(url: string, formData: FormData, config?: RequestConfig): Promise<T> {
    return service.post(url, formData, {
      ...config,
      headers: {
        'Content-Type': 'multipart/form-data',
        ...config?.headers
      }
    })
  },

  /**
   * 下载文件
   */
  download(url: string, params?: any, filename?: string): Promise<void> {
    return service
      .get(url, {
        params,
        responseType: 'blob'
      })
      .then((response: any) => {
        const blob = new Blob([response.data])
        const downloadUrl = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = downloadUrl
        link.download = filename || getFileNameFromResponse(response) || 'download'
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        window.URL.revokeObjectURL(downloadUrl)
      })
  }
}

// 从响应头获取文件名
function getFileNameFromResponse(response: AxiosResponse): string {
  const disposition = response.headers['content-disposition']
  if (disposition) {
    const match = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/)
    if (match && match[1]) {
      return decodeURIComponent(match[1].replace(/['"]/g, ''))
    }
  }
  return ''
}

export default request
export { service, type RequestConfig, type ResponseData }

import axios from 'axios'

// 创建 Axios 实例
const request = axios.create({
  baseURL: import.meta.env.VITE_SERVER_API, // 设置请求的基本URL
  timeout: 5000, // 设置请求超时时间
});

// 请求拦截器
request.interceptors.request.use(
  function (config) {
    // 在发送请求之前做些什么，例如添加认证信息、修改请求头等
    return config;
  },
  function (error) {
    // 对请求错误做些什么
    return Promise.reject(error);
  }
);

// 响应拦截器
request.interceptors.response.use(
  function (response) {
    // 对响应数据做些什么，例如解析响应数据、处理错误等
    return response.data;
  },
  function (error) {
    // 对响应错误做些什么
    return Promise.reject(error);
  }
);

export default request;
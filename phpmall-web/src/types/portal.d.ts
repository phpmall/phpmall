export interface ICaptchaResponse {
  captcha: string // 图片验证码
  uuid: string // 验证码UUID
}

export interface IUploadRequest {
  file: string // 文件
}

export interface IUploadResponse {
  url: string // 素材URL地址
}

export interface IRegionRequest {
  id: number // 地区ID
}

export interface IRegionResponse {
  id: number // 地区ID
  name: string // 地区名称
  first_letter: string // 地区名称首字母
}

export interface ISmsSendRequest {
  mobile: string // 手机号码
  captcha: string // 图片验证码
  uuid: string // 图片验证码UUID
}

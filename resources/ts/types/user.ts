export interface IAddressCreateRequest {
  mobile: string, // 手机号码
}

export interface IAddressQueryRequest {
  mobile: string, // 手机号码
}

export interface IAddressUpdateRequest {
  mobile: string, // 手机号码
}

export interface ILoginRequest {
  username: string, // 登录用户名
  password: string, // 登录密码
  captcha: string, // 图片验证码
  uuid: string, // 图片验证码UUID
}

export interface IAddressResponse {
  id: number, // 编号
}

export interface ILoginResponse {
  token: string, // JWT
}

export interface IProfileResponse {
  id: number, // 编号
  name: string, // 名称
}


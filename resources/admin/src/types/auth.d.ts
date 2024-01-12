export interface IForgetMobileRequest {
  mobile: string, // 手机号码
  captcha: string, // 图片验证码
}

export interface ILoginRequest {
  username: string, // 登录用户名
  password: string, // 登录密码
  remember?: string, // 记住我
  captcha: string, // 图片验证码
  uuid: string, // 图片验证码UUID
}

export interface ILoginSmsRequest {
  mobile: string, // 手机号码
  code: string, // 短信验证码
}

export interface IResetRequest {
  mobile: string, // 手机号码
  password: string, // 登录密码
  captcha: string, // 图片验证码
}

export interface ISignupMobileRequest {
  mobile: string, // 手机号码
  code: string, // 短信验证码
  agreed: boolean, // 注册协议
}

export interface ILoginResponse {
  token: string, // 用户JSON Web Token凭证
}


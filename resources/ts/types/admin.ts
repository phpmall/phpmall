export interface ILoginRequest {
  username: string, // 登录用户名
  password: string, // 登录密码
  captcha: string, // 图片验证码
  uuid: string, // 图片验证码UUID
}

export interface ILoginResponse {
  token: string, // JWT
}


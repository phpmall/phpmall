import type {ILoginMobileRequest} from "./models";
import {login} from "./api";

export const fetchUsers = async () => {
  try {
    const LoginRequest: ILoginMobileRequest = {
      mobile: 'string', // 手机号码
      password: 'string', // 登录密码
      captcha: 'string', // 图片验证码
      uuid: 'string', // 验证码UUID
    }
    const users = await login(LoginRequest);
    // 其他业务逻辑处理，如数据转换、数据处理等
    return users;
  } catch (error) {
    console.error(error);
    throw new Error('Failed to fetch users');
  }
};

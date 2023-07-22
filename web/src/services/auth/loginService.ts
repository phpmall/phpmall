import type {LoginRequest} from "@/models/passport";
import {login} from "@/services/auth/api/auth";

export const fetchUsers = async () => {
  try {
    const LoginRequest: LoginRequest = {
      name: '',
    }
    const users = await login(LoginRequest);
    // 其他业务逻辑处理，如数据转换、数据处理等
    return users;
  } catch (error) {
    console.error(error);
    throw new Error('Failed to fetch users');
  }
};

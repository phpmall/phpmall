import type { ILoginMobileRequest } from '../models';
import request from '@/utils/request'

export const loginApi = async (formData: ILoginMobileRequest) => {
  try {
    const response = await request.post('/auth/login', formData);
    return response.data;
  } catch (error) {
    console.error(error);
    throw new Error('Failed to fetch users');
  }
};

export const captchaApi = async () => {
  try {
    const response = await request.get(`/auth/captcha?t=?${Date.now()}`);
    return response.data;
  } catch (error) {
    console.error(error);
    throw new Error('Failed to fetch users');
  }
};

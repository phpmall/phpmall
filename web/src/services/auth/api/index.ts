import type { LoginRequest } from '@/models/passport';
import request from '@/utils/request'

export const login = async (loginRequest: LoginRequest) => {
  try {
    const response = await request.post('/auth/login', loginRequest);
    return response.data;
  } catch (error) {
    console.error(error);
    throw new Error('Failed to fetch users');
  }
};

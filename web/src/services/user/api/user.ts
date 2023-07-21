import request from '@/utils/request'

// 获取用户列表
export const getUsers = async () => {
  try {
    const response = await request.get('/users');
    return response.data;
  } catch (error) {
    console.error(error);
    throw new Error('Failed to fetch users');
  }
};

// 创建用户
export const createUser = async (userData) => {
  try {
    const response = await request.post('/users', userData);
    return response.data;
  } catch (error) {
    console.error(error);
    throw new Error('Failed to create user');
  }
};

// 获取单个用户的信息
export const getUser = async (userId) => {
  try {
    const response = await request.get(`/users/${userId}`);
    return response.data;
  } catch (error) {
    console.error(error);
    throw new Error(`Failed to fetch user with id ${userId}`);
  }
};

// 其他用户相关的API函数...
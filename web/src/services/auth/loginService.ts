import { getUsers, createUser, getUser } from '@/api/user';

// 获取用户列表
export const fetchUsers = async () => {
  try {
    const users = await getUsers();
    // 其他业务逻辑处理，如数据转换、数据处理等
    return users;
  } catch (error) {
    console.error(error);
    throw new Error('Failed to fetch users');
  }
};

// 创建用户
export const addUser = async (userData) => {
  try {
    const newUser = await createUser(userData);
    // 其他业务逻辑处理...
    return newUser;
  } catch (error) {
    console.error(error);
    throw new Error('Failed to create user');
  }
};

// 获取单个用户的信息
export const fetchUser = async (userId) => {
  try {
    const user = await getUser(userId);
    // 其他业务逻辑处理...
    return user;
  } catch (error) {
    console.error(error);
    throw new Error(`Failed to fetch user with id ${userId}`);
  }
};

// 其他用户相关的服务函数...
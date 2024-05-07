export interface IForgetMobileRequest {
  mobile: string, // 手机号码
  captcha: string, // 图片验证码
  uuid: string, // 图片验证码UUID
}

export interface ILoginRequest {
  username: string, // 登录用户名
  password: string, // 登录密码
  captcha: string, // 图片验证码
  uuid: string, // 图片验证码UUID
  remember?: string, // 记住我
}

export interface ILoginSmsRequest {
  mobile: string, // 手机号码
  code: string, // 短信验证码
}

export interface IResetRequest {
  mobile: string, // 手机号码
  password: string, // 登录密码
  captcha: string, // 图片验证码
  uuid: string, // 图片验证码UUID
}

export interface ISignupMobileRequest {
  mobile: string, // 手机号码
  code: string, // 短信验证码
  accept_term: boolean, // 是否接受注册协议
}

export interface ILoginResponse {
  token: string, // 用户JSON Web Token凭证
}

export interface ICaptchaResponse {
  captcha: string, // 图片验证码
  uuid: string, // 验证码UUID
}

export interface IRegionRequest {
  id: number, // 地区ID
}

export interface IRegionResponse {
  id: number, // 地区ID
  name: string, // 地区名称
  first_letter: string, // 地区名称首字母
}

export interface ISmsSendRequest {
  mobile: string, // 手机号码
  captcha: string, // 图片验证码
  uuid: string, // 图片验证码UUID
}

export interface IPermissionCreateRequest {
  parent_id: number, // 父级ID
  module: string, // 模块名:如manager,merchant
  icon: string, // 菜单图标
  name: string, // 资源名称
  resource: string, // 资源标识
  menu: number, // 是否为菜单项:1是,0否
  sort: number, // 排序
  status: number, // 状态:1正常,2禁用
}

export interface IPermissionQueryRequest {
}

export interface IPermissionUpdateRequest {
  id: number, // ID
  parent_id: number, // 父级ID
  module: string, // 模块名:如manager,merchant
  icon: string, // 菜单图标
  name: string, // 资源名称
  resource: string, // 资源标识
  menu: number, // 是否为菜单项:1是,0否
  sort: number, // 排序
  status: number, // 状态:1正常,2禁用
}

export interface IRoleCreateRequest {
  name: string, // 角色名称
  code: string, // 角色代码
  description: string, // 角色描述
  sort: number, // 排序
  status: number, // 状态:1正常,2禁用
}

export interface IRoleQueryRequest {
}

export interface IRoleUpdateRequest {
  id: number, // ID
  name: string, // 角色名称
  code: string, // 角色代码
  description: string, // 角色描述
  sort: number, // 排序
  status: number, // 状态:1正常,2禁用
}

export interface IRolePermissionCreateRequest {
  role_id: number, // 角色ID
  permission_id: number, // 权限资源ID
}

export interface IRolePermissionQueryRequest {
}

export interface IRolePermissionUpdateRequest {
  id: number, // ID
  role_id: number, // 角色ID
  permission_id: number, // 权限资源ID
}

export interface IUserCreateRequest {
  uuid: string, // 全局ID
  name: string, // 昵称
  avatar: string, // 头像
  mobile: string, // 手机号码
  mobile_verified_time: string, // 手机号验证时间
  password: string, // 登录密码
  remember_token: string, // 
}

export interface IUserQueryRequest {
}

export interface IUserUpdateRequest {
  id: number, // ID
  uuid: string, // 全局ID
  name: string, // 昵称
  avatar: string, // 头像
  mobile: string, // 手机号码
  mobile_verified_time: string, // 手机号验证时间
  password: string, // 登录密码
  remember_token: string, // 
}

export interface IUserLogCreateRequest {
  user_id: number, // 用户ID
  event_type: string, // 事件类型，用于区分不同的用户操作或系统事件
  event_time: string, // 事件发生的时间
  event_details: string, // 事件的详细信息，推荐json格式
  ip_address: string, // 用户的IP地址
  user_agent: string, // 用户代理字符串
}

export interface IUserLogQueryRequest {
}

export interface IUserLogUpdateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  event_type: string, // 事件类型，用于区分不同的用户操作或系统事件
  event_time: string, // 事件发生的时间
  event_details: string, // 事件的详细信息，推荐json格式
  ip_address: string, // 用户的IP地址
  user_agent: string, // 用户代理字符串
}

export interface IUserPermissionCreateRequest {
  user_id: number, // 用户ID
  permission_id: number, // 权限资源ID
}

export interface IUserPermissionQueryRequest {
}

export interface IUserPermissionUpdateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  permission_id: number, // 权限资源ID
}

export interface IUserRoleCreateRequest {
  user_id: number, // 用户ID
  role_id: number, // 角色ID
}

export interface IUserRoleQueryRequest {
}

export interface IUserRoleUpdateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  role_id: number, // 角色ID
}

export interface IPermissionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IPermissionQueryResponse {
  current_page: number, // 当前页码
  data: IPermissionResponse[], // 数据列表
  first_page_url: string, // 首页URL
  from: number, // 当前页面上的开始位置
  last_page: number, // 最后页码
  last_page_url: string, // 最后页URL
  links: IPaginateLinkVo[], // 分页链接的数组
  next_page_url: string, // 下一页URL
  path: string, // 分页URL
  per_page: number, // 每页显示的记录数量
  prev_page_url: string, // 上一页URL
  to: number, // 当前页面上的最后位置
  total: number, // 数据总数
}

export interface IPermissionResponse {
  id: number, // ID
  parentId: number, // 父级ID
  module: string, // 模块名:如manager,merchant
  icon: string, // 菜单图标
  name: string, // 资源名称
  resource: string, // 资源标识
  menu: number, // 是否为菜单项:1是,0否
  sort: number, // 排序
  status: number, // 状态:1正常,2禁用
  createdAt: string, // 
  updatedAt: string, // 
  deletedAt: string, // 
}

export interface IRoleDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IRoleQueryResponse {
  current_page: number, // 当前页码
  data: IRoleResponse[], // 数据列表
  first_page_url: string, // 首页URL
  from: number, // 当前页面上的开始位置
  last_page: number, // 最后页码
  last_page_url: string, // 最后页URL
  links: IPaginateLinkVo[], // 分页链接的数组
  next_page_url: string, // 下一页URL
  path: string, // 分页URL
  per_page: number, // 每页显示的记录数量
  prev_page_url: string, // 上一页URL
  to: number, // 当前页面上的最后位置
  total: number, // 数据总数
}

export interface IRoleResponse {
  id: number, // ID
  name: string, // 角色名称
  code: string, // 角色代码
  description: string, // 角色描述
  sort: number, // 排序
  status: number, // 状态:1正常,2禁用
  createdAt: string, // 
  updatedAt: string, // 
  deletedAt: string, // 
}

export interface IRolePermissionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IRolePermissionQueryResponse {
  current_page: number, // 当前页码
  data: IRolePermissionResponse[], // 数据列表
  first_page_url: string, // 首页URL
  from: number, // 当前页面上的开始位置
  last_page: number, // 最后页码
  last_page_url: string, // 最后页URL
  links: IPaginateLinkVo[], // 分页链接的数组
  next_page_url: string, // 下一页URL
  path: string, // 分页URL
  per_page: number, // 每页显示的记录数量
  prev_page_url: string, // 上一页URL
  to: number, // 当前页面上的最后位置
  total: number, // 数据总数
}

export interface IRolePermissionResponse {
  id: number, // ID
  roleId: number, // 角色ID
  permissionId: number, // 权限资源ID
}

export interface IUserDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserQueryResponse {
  current_page: number, // 当前页码
  data: IUserResponse[], // 数据列表
  first_page_url: string, // 首页URL
  from: number, // 当前页面上的开始位置
  last_page: number, // 最后页码
  last_page_url: string, // 最后页URL
  links: IPaginateLinkVo[], // 分页链接的数组
  next_page_url: string, // 下一页URL
  path: string, // 分页URL
  per_page: number, // 每页显示的记录数量
  prev_page_url: string, // 上一页URL
  to: number, // 当前页面上的最后位置
  total: number, // 数据总数
}

export interface IUserResponse {
  id: number, // ID
  uuid: string, // 全局ID
  name: string, // 昵称
  avatar: string, // 头像
  mobile: string, // 手机号码
  mobileVerifiedTime: string, // 手机号验证时间
  rememberToken: string, // 
  createdAt: string, // 
  updatedAt: string, // 
  deletedAt: string, // 
}

export interface IUserLogDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserLogQueryResponse {
  current_page: number, // 当前页码
  data: IUserLogResponse[], // 数据列表
  first_page_url: string, // 首页URL
  from: number, // 当前页面上的开始位置
  last_page: number, // 最后页码
  last_page_url: string, // 最后页URL
  links: IPaginateLinkVo[], // 分页链接的数组
  next_page_url: string, // 下一页URL
  path: string, // 分页URL
  per_page: number, // 每页显示的记录数量
  prev_page_url: string, // 上一页URL
  to: number, // 当前页面上的最后位置
  total: number, // 数据总数
}

export interface IUserLogResponse {
  id: number, // ID
  userId: number, // 用户ID
  eventType: string, // 事件类型，用于区分不同的用户操作或系统事件
  eventTime: string, // 事件发生的时间
  eventDetails: string, // 事件的详细信息，推荐json格式
  ipAddress: string, // 用户的IP地址
  userAgent: string, // 用户代理字符串
  createdAt: string, // 
  updatedAt: string, // 
  deletedAt: string, // 
}

export interface IUserPermissionDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserPermissionQueryResponse {
  current_page: number, // 当前页码
  data: IUserPermissionResponse[], // 数据列表
  first_page_url: string, // 首页URL
  from: number, // 当前页面上的开始位置
  last_page: number, // 最后页码
  last_page_url: string, // 最后页URL
  links: IPaginateLinkVo[], // 分页链接的数组
  next_page_url: string, // 下一页URL
  path: string, // 分页URL
  per_page: number, // 每页显示的记录数量
  prev_page_url: string, // 上一页URL
  to: number, // 当前页面上的最后位置
  total: number, // 数据总数
}

export interface IUserPermissionResponse {
  id: number, // ID
  userId: number, // 用户ID
  permissionId: number, // 权限资源ID
}

export interface IUserRoleDestroyResponse {
  status: number, // 状态:1成功，2失败
}

export interface IUserRoleQueryResponse {
  current_page: number, // 当前页码
  data: IUserRoleResponse[], // 数据列表
  first_page_url: string, // 首页URL
  from: number, // 当前页面上的开始位置
  last_page: number, // 最后页码
  last_page_url: string, // 最后页URL
  links: IPaginateLinkVo[], // 分页链接的数组
  next_page_url: string, // 下一页URL
  path: string, // 分页URL
  per_page: number, // 每页显示的记录数量
  prev_page_url: string, // 上一页URL
  to: number, // 当前页面上的最后位置
  total: number, // 数据总数
}

export interface IUserRoleResponse {
  id: number, // ID
  userId: number, // 用户ID
  roleId: number, // 角色ID
}

export interface IAddressCreateRequest {
  mobile: string, // 手机号码
}

export interface IAddressQueryRequest {
  mobile: string, // 手机号码
}

export interface IAddressUpdateRequest {
  mobile: string, // 手机号码
}

export interface IProfileRequest {
  name: string, // 名称
}

export interface IAddressResponse {
  id: number, // 编号
}

export interface IProfileResponse {
  id: number, // 编号
  name: string, // 名称
}

export interface IOptionResponse {
  name: string, // 名称
  val: number, // 值
}

export interface IPaginateLinkVo {
  url: string, // 链接URL
  label: string, // 页标签
  next: boolean, // 当前页
}


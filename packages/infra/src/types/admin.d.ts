export interface IAdminVo {
  id: number, // 用户ID
  username: string, // 用户名
}

export interface ILoginResponse {
  user: IAdminVo,
  token: string, // 访问令牌
  refreshToken: string, // 刷新令牌
  expiresIn: number, // 令牌过期时间（秒）
}

export interface IArticleCreateRequest {
  id: number, // ID
  category_id: number, // 分类ID
  user_id: number, // 作者ID
  title: string, // 文章标题
  slug: string, // 文章别名
  summary: string, // 文章摘要
  content: string, // 文章内容
  cover_image: string, // 封面图
  images: string, // 图片集(JSON)
  view_count: number, // 浏览次数
  like_count: number, // 点赞次数
  comment_count: number, // 评论次数
  is_recommend: number, // 是否推荐: 0-否;1-是
  is_top: number, // 是否置顶: 0-否;1-是
  is_hot: number, // 是否热门: 0-否;1-是
  publish_status: number, // 发布状态: 1-草稿;2-已发布;3-下架
  publish_time: string, // 发布时间
  seo_title: string, // SEO标题
  seo_keywords: string, // SEO关键词
  seo_description: string, // SEO描述
  sort: number, // 排序
  template: string, // 模板
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface IArticleQueryRequest {
}

export interface IArticleUpdateRequest {
  id: number, // ID
  category_id: number, // 分类ID
  user_id: number, // 作者ID
  title: string, // 文章标题
  slug: string, // 文章别名
  summary: string, // 文章摘要
  content: string, // 文章内容
  cover_image: string, // 封面图
  images: string, // 图片集(JSON)
  view_count: number, // 浏览次数
  like_count: number, // 点赞次数
  comment_count: number, // 评论次数
  is_recommend: number, // 是否推荐: 0-否;1-是
  is_top: number, // 是否置顶: 0-否;1-是
  is_hot: number, // 是否热门: 0-否;1-是
  publish_status: number, // 发布状态: 1-草稿;2-已发布;3-下架
  publish_time: string, // 发布时间
  seo_title: string, // SEO标题
  seo_keywords: string, // SEO关键词
  seo_description: string, // SEO描述
  sort: number, // 排序
  template: string, // 模板
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface IArticleTagCreateRequest {
  id: number, // ID
  article_id: number, // 文章ID
  tag_id: number, // 标签ID
  created_time: string, // 创建时间
}

export interface IArticleTagQueryRequest {
}

export interface IArticleTagUpdateRequest {
  id: number, // ID
  article_id: number, // 文章ID
  tag_id: number, // 标签ID
  created_time: string, // 创建时间
}

export interface IArticleQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IArticleResponse[], // 数据列表
}

export interface IArticleResponse {
  id: number, // ID
  categoryId: number, // 分类ID
  userId: number, // 作者ID
  title: string, // 文章标题
  slug: string, // 文章别名
  summary: string, // 文章摘要
  content: string, // 文章内容
  coverImage: string, // 封面图
  images: string, // 图片集(JSON)
  viewCount: number, // 浏览次数
  likeCount: number, // 点赞次数
  commentCount: number, // 评论次数
  isRecommend: number, // 是否推荐: 0-否;1-是
  isTop: number, // 是否置顶: 0-否;1-是
  isHot: number, // 是否热门: 0-否;1-是
  publishStatus: number, // 发布状态: 1-草稿;2-已发布;3-下架
  publishTime: string, // 发布时间
  seoTitle: string, // SEO标题
  seoKeywords: string, // SEO关键词
  seoDescription: string, // SEO描述
  sort: number, // 排序
  template: string, // 模板
  deleted: number, // 删除状态: 0-未删除;1-已删除
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
  deletedTime: string, // 删除时间
}

export interface IArticleTagQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IArticleTagResponse[], // 数据列表
}

export interface IArticleTagResponse {
  id: number, // ID
  articleId: number, // 文章ID
  tagId: number, // 标签ID
  createdTime: string, // 创建时间
}

export interface IAttachmentCreateRequest {
  id: number, // ID
  user_id: number, // 上传用户ID
  category_id: number, // 分类ID
  name: string, // 文件名
  original_name: string, // 原始文件名
  path: string, // 文件路径
  url: string, // 访问URL
  storage_type: string, // 存储类型: local-本地;oss-阿里云;qiniu-七牛云;cos-腾讯云
  file_type: string, // 文件类型: image-图片;video-视频;audio-音频;document-文档
  mime_type: string, // MIME类型
  size: number, // 文件大小(字节)
  ext: string, // 文件扩展名
  width: number, // 宽度(图片/视频)
  height: number, // 高度(图片/视频)
  duration: number, // 时长(音视频,秒)
  md5: string, // MD5值
  sha1: string, // SHA1值
  download_count: number, // 下载次数
  status: number, // 状态: 1-正常;2-禁用
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface IAttachmentQueryRequest {
}

export interface IAttachmentUpdateRequest {
  id: number, // ID
  user_id: number, // 上传用户ID
  category_id: number, // 分类ID
  name: string, // 文件名
  original_name: string, // 原始文件名
  path: string, // 文件路径
  url: string, // 访问URL
  storage_type: string, // 存储类型: local-本地;oss-阿里云;qiniu-七牛云;cos-腾讯云
  file_type: string, // 文件类型: image-图片;video-视频;audio-音频;document-文档
  mime_type: string, // MIME类型
  size: number, // 文件大小(字节)
  ext: string, // 文件扩展名
  width: number, // 宽度(图片/视频)
  height: number, // 高度(图片/视频)
  duration: number, // 时长(音视频,秒)
  md5: string, // MD5值
  sha1: string, // SHA1值
  download_count: number, // 下载次数
  status: number, // 状态: 1-正常;2-禁用
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface IAttachmentCategoryCreateRequest {
  id: number, // ID
  parent_id: number, // 上级分类ID
  name: string, // 分类名称
  type: string, // 分类类型: image-图片;video-视频;audio-音频;document-文档
  sort: number, // 排序
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IAttachmentCategoryQueryRequest {
}

export interface IAttachmentCategoryUpdateRequest {
  id: number, // ID
  parent_id: number, // 上级分类ID
  name: string, // 分类名称
  type: string, // 分类类型: image-图片;video-视频;audio-音频;document-文档
  sort: number, // 排序
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IAttachmentQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IAttachmentResponse[], // 数据列表
}

export interface IAttachmentResponse {
  id: number, // ID
  userId: number, // 上传用户ID
  categoryId: number, // 分类ID
  name: string, // 文件名
  originalName: string, // 原始文件名
  path: string, // 文件路径
  url: string, // 访问URL
  storageType: string, // 存储类型: local-本地;oss-阿里云;qiniu-七牛云;cos-腾讯云
  fileType: string, // 文件类型: image-图片;video-视频;audio-音频;document-文档
  mimeType: string, // MIME类型
  size: number, // 文件大小(字节)
  ext: string, // 文件扩展名
  width: number, // 宽度(图片/视频)
  height: number, // 高度(图片/视频)
  duration: number, // 时长(音视频,秒)
  md5: string, // MD5值
  sha1: string, // SHA1值
  downloadCount: number, // 下载次数
  status: number, // 状态: 1-正常;2-禁用
  deleted: number, // 删除状态: 0-未删除;1-已删除
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
  deletedTime: string, // 删除时间
}

export interface IAttachmentCategoryQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IAttachmentCategoryResponse[], // 数据列表
}

export interface IAttachmentCategoryResponse {
  id: number, // ID
  parentId: number, // 上级分类ID
  name: string, // 分类名称
  type: string, // 分类类型: image-图片;video-视频;audio-音频;document-文档
  sort: number, // 排序
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface IBannerCreateRequest {
  id: number, // ID
  position_id: number, // 广告位ID
  title: string, // 标题
  image: string, // 图片
  link: string, // 链接
  target: string, // 打开方式: _blank-新窗口;_self-当前窗口
  start_time: string, // 开始时间
  end_time: string, // 结束时间
  sort: number, // 排序
  click_count: number, // 点击次数
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IBannerQueryRequest {
}

export interface IBannerUpdateRequest {
  id: number, // ID
  position_id: number, // 广告位ID
  title: string, // 标题
  image: string, // 图片
  link: string, // 链接
  target: string, // 打开方式: _blank-新窗口;_self-当前窗口
  start_time: string, // 开始时间
  end_time: string, // 结束时间
  sort: number, // 排序
  click_count: number, // 点击次数
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IBannerPositionCreateRequest {
  id: number, // ID
  name: string, // 广告位名称
  code: string, // 调用标识
  description: string, // 描述
  width: number, // 推荐宽度
  height: number, // 推荐高度
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IBannerPositionQueryRequest {
}

export interface IBannerPositionUpdateRequest {
  id: number, // ID
  name: string, // 广告位名称
  code: string, // 调用标识
  description: string, // 描述
  width: number, // 推荐宽度
  height: number, // 推荐高度
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IBannerQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IBannerResponse[], // 数据列表
}

export interface IBannerResponse {
  id: number, // ID
  positionId: number, // 广告位ID
  title: string, // 标题
  image: string, // 图片
  link: string, // 链接
  target: string, // 打开方式: _blank-新窗口;_self-当前窗口
  startTime: string, // 开始时间
  endTime: string, // 结束时间
  sort: number, // 排序
  clickCount: number, // 点击次数
  status: number, // 状态: 1-正常;2-禁用
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface IBannerPositionQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IBannerPositionResponse[], // 数据列表
}

export interface IBannerPositionResponse {
  id: number, // ID
  name: string, // 广告位名称
  code: string, // 调用标识
  description: string, // 描述
  width: number, // 推荐宽度
  height: number, // 推荐高度
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface ICategoryCreateRequest {
  id: number, // ID
  parent_id: number, // 上级分类ID
  name: string, // 分类名称
  slug: string, // 分类别名
  description: string, // 分类描述
  type: string, // 分类类型: article-文章;product-产品;custom-自定义
  sort: number, // 排序
  icon: string, // 图标
  path: string, // 分类路径
  status: number, // 状态: 1-正常;2-禁用
  seo_title: string, // SEO标题
  seo_keywords: string, // SEO关键词
  seo_description: string, // SEO描述
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface ICategoryQueryRequest {
}

export interface ICategoryUpdateRequest {
  id: number, // ID
  parent_id: number, // 上级分类ID
  name: string, // 分类名称
  slug: string, // 分类别名
  description: string, // 分类描述
  type: string, // 分类类型: article-文章;product-产品;custom-自定义
  sort: number, // 排序
  icon: string, // 图标
  path: string, // 分类路径
  status: number, // 状态: 1-正常;2-禁用
  seo_title: string, // SEO标题
  seo_keywords: string, // SEO关键词
  seo_description: string, // SEO描述
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface ICategoryQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ICategoryResponse[], // 数据列表
}

export interface ICategoryResponse {
  id: number, // ID
  parentId: number, // 上级分类ID
  name: string, // 分类名称
  slug: string, // 分类别名
  description: string, // 分类描述
  type: string, // 分类类型: article-文章;product-产品;custom-自定义
  sort: number, // 排序
  icon: string, // 图标
  path: string, // 分类路径
  status: number, // 状态: 1-正常;2-禁用
  seoTitle: string, // SEO标题
  seoKeywords: string, // SEO关键词
  seoDescription: string, // SEO描述
  deleted: number, // 删除状态: 0-未删除;1-已删除
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
  deletedTime: string, // 删除时间
}

export interface ICommentCreateRequest {
  id: number, // ID
  parent_id: number, // 父评论ID
  user_id: number, // 用户ID
  commentable_type: string, // 评论对象类型
  commentable_id: number, // 评论对象ID
  content: string, // 评论内容
  ip: string, // IP地址
  user_agent: string, // User Agent
  like_count: number, // 点赞次数
  status: number, // 状态: 1-待审核;2-已发布;3-已拒绝
  is_top: number, // 是否置顶: 0-否;1-是
  is_hot: number, // 是否热门: 0-否;1-是
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface ICommentQueryRequest {
}

export interface ICommentUpdateRequest {
  id: number, // ID
  parent_id: number, // 父评论ID
  user_id: number, // 用户ID
  commentable_type: string, // 评论对象类型
  commentable_id: number, // 评论对象ID
  content: string, // 评论内容
  ip: string, // IP地址
  user_agent: string, // User Agent
  like_count: number, // 点赞次数
  status: number, // 状态: 1-待审核;2-已发布;3-已拒绝
  is_top: number, // 是否置顶: 0-否;1-是
  is_hot: number, // 是否热门: 0-否;1-是
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface ICommentQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ICommentResponse[], // 数据列表
}

export interface ICommentResponse {
  id: number, // ID
  parentId: number, // 父评论ID
  userId: number, // 用户ID
  commentableType: string, // 评论对象类型
  commentableId: number, // 评论对象ID
  content: string, // 评论内容
  ip: string, // IP地址
  userAgent: string, // User Agent
  likeCount: number, // 点赞次数
  status: number, // 状态: 1-待审核;2-已发布;3-已拒绝
  isTop: number, // 是否置顶: 0-否;1-是
  isHot: number, // 是否热门: 0-否;1-是
  deleted: number, // 删除状态: 0-未删除;1-已删除
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
  deletedTime: string, // 删除时间
}

export interface IDictCreateRequest {
  id: number, // ID
  parent_id: number, // 父字典ID
  dict_type: string, // 字典类型
  dict_label: string, // 字典标签
  dict_value: string, // 字典值
  sort: number, // 排序
  status: number, // 状态: 1-正常;2-禁用
  remark: string, // 备注
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IDictQueryRequest {
}

export interface IDictUpdateRequest {
  id: number, // ID
  parent_id: number, // 父字典ID
  dict_type: string, // 字典类型
  dict_label: string, // 字典标签
  dict_value: string, // 字典值
  sort: number, // 排序
  status: number, // 状态: 1-正常;2-禁用
  remark: string, // 备注
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IDictQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IDictResponse[], // 数据列表
}

export interface IDictResponse {
  id: number, // ID
  parentId: number, // 父字典ID
  dictType: string, // 字典类型
  dictLabel: string, // 字典标签
  dictValue: string, // 字典值
  sort: number, // 排序
  status: number, // 状态: 1-正常;2-禁用
  remark: string, // 备注
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface IFavoriteCreateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  favorable_type: string, // 收藏对象类型
  favorable_id: number, // 收藏对象ID
  created_time: string, // 创建时间
}

export interface IFavoriteQueryRequest {
}

export interface IFavoriteUpdateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  favorable_type: string, // 收藏对象类型
  favorable_id: number, // 收藏对象ID
  created_time: string, // 创建时间
}

export interface IFavoriteQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IFavoriteResponse[], // 数据列表
}

export interface IFavoriteResponse {
  id: number, // ID
  userId: number, // 用户ID
  favorableType: string, // 收藏对象类型
  favorableId: number, // 收藏对象ID
  createdTime: string, // 创建时间
}

export interface ILikeCreateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  likeable_type: string, // 点赞对象类型
  likeable_id: number, // 点赞对象ID
  created_time: string, // 创建时间
}

export interface ILikeQueryRequest {
}

export interface ILikeUpdateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  likeable_type: string, // 点赞对象类型
  likeable_id: number, // 点赞对象ID
  created_time: string, // 创建时间
}

export interface ILikeQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ILikeResponse[], // 数据列表
}

export interface ILikeResponse {
  id: number, // ID
  userId: number, // 用户ID
  likeableType: string, // 点赞对象类型
  likeableId: number, // 点赞对象ID
  createdTime: string, // 创建时间
}

export interface ILinkCreateRequest {
  id: number, // ID
  category_id: number, // 分类ID
  title: string, // 标题
  url: string, // 链接
  logo: string, // LOGO
  description: string, // 描述
  rating: number, // 星级: 1-5
  sort: number, // 排序
  target: string, // 打开方式: _blank-新窗口;_self-当前窗口
  nofollow: number, // 是否nofollow: 0-否;1-是
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ILinkQueryRequest {
}

export interface ILinkUpdateRequest {
  id: number, // ID
  category_id: number, // 分类ID
  title: string, // 标题
  url: string, // 链接
  logo: string, // LOGO
  description: string, // 描述
  rating: number, // 星级: 1-5
  sort: number, // 排序
  target: string, // 打开方式: _blank-新窗口;_self-当前窗口
  nofollow: number, // 是否nofollow: 0-否;1-是
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ILinkQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ILinkResponse[], // 数据列表
}

export interface ILinkResponse {
  id: number, // ID
  categoryId: number, // 分类ID
  title: string, // 标题
  url: string, // 链接
  logo: string, // LOGO
  description: string, // 描述
  rating: number, // 星级: 1-5
  sort: number, // 排序
  target: string, // 打开方式: _blank-新窗口;_self-当前窗口
  nofollow: number, // 是否nofollow: 0-否;1-是
  status: number, // 状态: 1-正常;2-禁用
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface ILoginLogCreateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  username: string, // 用户名
  user_type: string, // 用户类型: admin-管理员;user-用户
  ip: string, // IP地址
  location: string, // 登录地点
  browser: string, // 浏览器
  os: string, // 操作系统
  login_status: number, // 登录状态: 1-成功;2-失败
  message: string, // 提示信息
  created_time: string, // 创建时间
}

export interface ILoginLogQueryRequest {
}

export interface ILoginLogUpdateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  username: string, // 用户名
  user_type: string, // 用户类型: admin-管理员;user-用户
  ip: string, // IP地址
  location: string, // 登录地点
  browser: string, // 浏览器
  os: string, // 操作系统
  login_status: number, // 登录状态: 1-成功;2-失败
  message: string, // 提示信息
  created_time: string, // 创建时间
}

export interface ILoginLogQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ILoginLogResponse[], // 数据列表
}

export interface ILoginLogResponse {
  id: number, // ID
  userId: number, // 用户ID
  username: string, // 用户名
  userType: string, // 用户类型: admin-管理员;user-用户
  ip: string, // IP地址
  location: string, // 登录地点
  browser: string, // 浏览器
  os: string, // 操作系统
  loginStatus: number, // 登录状态: 1-成功;2-失败
  message: string, // 提示信息
  createdTime: string, // 创建时间
}

export interface INavigationCreateRequest {
  id: number, // ID
  parent_id: number, // 上级导航ID
  position: string, // 导航位置: header-顶部;footer-底部;side-侧边
  title: string, // 标题
  url: string, // 链接
  icon: string, // 图标
  target: string, // 打开方式: _blank-新窗口;_self-当前窗口
  sort: number, // 排序
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface INavigationQueryRequest {
}

export interface INavigationUpdateRequest {
  id: number, // ID
  parent_id: number, // 上级导航ID
  position: string, // 导航位置: header-顶部;footer-底部;side-侧边
  title: string, // 标题
  url: string, // 链接
  icon: string, // 图标
  target: string, // 打开方式: _blank-新窗口;_self-当前窗口
  sort: number, // 排序
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface INavigationQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: INavigationResponse[], // 数据列表
}

export interface INavigationResponse {
  id: number, // ID
  parentId: number, // 上级导航ID
  position: string, // 导航位置: header-顶部;footer-底部;side-侧边
  title: string, // 标题
  url: string, // 链接
  icon: string, // 图标
  target: string, // 打开方式: _blank-新窗口;_self-当前窗口
  sort: number, // 排序
  status: number, // 状态: 1-正常;2-禁用
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface INotificationCreateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  type: string, // 通知类型: system-系统;message-私信;comment-评论;like-点赞
  title: string, // 标题
  content: string, // 内容
  link: string, // 跳转链接
  is_read: number, // 是否已读: 0-未读;1-已读
  read_time: string, // 阅读时间
  created_time: string, // 创建时间
}

export interface INotificationQueryRequest {
}

export interface INotificationUpdateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  type: string, // 通知类型: system-系统;message-私信;comment-评论;like-点赞
  title: string, // 标题
  content: string, // 内容
  link: string, // 跳转链接
  is_read: number, // 是否已读: 0-未读;1-已读
  read_time: string, // 阅读时间
  created_time: string, // 创建时间
}

export interface INotificationQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: INotificationResponse[], // 数据列表
}

export interface INotificationResponse {
  id: number, // ID
  userId: number, // 用户ID
  type: string, // 通知类型: system-系统;message-私信;comment-评论;like-点赞
  title: string, // 标题
  content: string, // 内容
  link: string, // 跳转链接
  isRead: number, // 是否已读: 0-未读;1-已读
  readTime: string, // 阅读时间
  createdTime: string, // 创建时间
}

export interface IOperationLogCreateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  username: string, // 用户名
  module: string, // 模块
  action: string, // 操作
  method: string, // 请求方法
  url: string, // 请求URL
  ip: string, // IP地址
  user_agent: string, // User Agent
  request_data: string, // 请求数据
  response_data: string, // 响应数据
  execute_time: number, // 执行时长(毫秒)
  status: number, // 状态: 1-成功;2-失败
  created_time: string, // 创建时间
}

export interface IOperationLogQueryRequest {
}

export interface IOperationLogUpdateRequest {
  id: number, // ID
  user_id: number, // 用户ID
  username: string, // 用户名
  module: string, // 模块
  action: string, // 操作
  method: string, // 请求方法
  url: string, // 请求URL
  ip: string, // IP地址
  user_agent: string, // User Agent
  request_data: string, // 请求数据
  response_data: string, // 响应数据
  execute_time: number, // 执行时长(毫秒)
  status: number, // 状态: 1-成功;2-失败
  created_time: string, // 创建时间
}

export interface IOperationLogQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IOperationLogResponse[], // 数据列表
}

export interface IOperationLogResponse {
  id: number, // ID
  userId: number, // 用户ID
  username: string, // 用户名
  module: string, // 模块
  action: string, // 操作
  method: string, // 请求方法
  url: string, // 请求URL
  ip: string, // IP地址
  userAgent: string, // User Agent
  requestData: string, // 请求数据
  responseData: string, // 响应数据
  executeTime: number, // 执行时长(毫秒)
  status: number, // 状态: 1-成功;2-失败
  createdTime: string, // 创建时间
}

export interface IPageCreateRequest {
  id: number, // ID
  title: string, // 页面标题
  slug: string, // 页面别名
  content: string, // 页面内容
  template: string, // 模板
  keywords: string, // 关键词
  description: string, // 描述
  view_count: number, // 浏览次数
  status: number, // 状态: 1-正常;2-禁用
  sort: number, // 排序
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IPageQueryRequest {
}

export interface IPageUpdateRequest {
  id: number, // ID
  title: string, // 页面标题
  slug: string, // 页面别名
  content: string, // 页面内容
  template: string, // 模板
  keywords: string, // 关键词
  description: string, // 描述
  view_count: number, // 浏览次数
  status: number, // 状态: 1-正常;2-禁用
  sort: number, // 排序
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface IPageQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IPageResponse[], // 数据列表
}

export interface IPageResponse {
  id: number, // ID
  title: string, // 页面标题
  slug: string, // 页面别名
  content: string, // 页面内容
  template: string, // 模板
  keywords: string, // 关键词
  description: string, // 描述
  viewCount: number, // 浏览次数
  status: number, // 状态: 1-正常;2-禁用
  sort: number, // 排序
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface ISettingCreateRequest {
  id: number, // ID
  group: string, // 配置分组: basic-基础;seo-SEO;upload-上传;email-邮件;sms-短信
  key: string, // 配置键
  value: string, // 配置值
  type: string, // 类型: text-文本;textarea-多行文本;radio-单选;checkbox-多选;image-图片;file-文件
  title: string, // 配置标题
  description: string, // 配置描述
  sort: number, // 排序
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISettingQueryRequest {
}

export interface ISettingUpdateRequest {
  id: number, // ID
  group: string, // 配置分组: basic-基础;seo-SEO;upload-上传;email-邮件;sms-短信
  key: string, // 配置键
  value: string, // 配置值
  type: string, // 类型: text-文本;textarea-多行文本;radio-单选;checkbox-多选;image-图片;file-文件
  title: string, // 配置标题
  description: string, // 配置描述
  sort: number, // 排序
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISettingQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ISettingResponse[], // 数据列表
}

export interface ISettingResponse {
  id: number, // ID
  group: string, // 配置分组: basic-基础;seo-SEO;upload-上传;email-邮件;sms-短信
  key: string, // 配置键
  value: string, // 配置值
  type: string, // 类型: text-文本;textarea-多行文本;radio-单选;checkbox-多选;image-图片;file-文件
  title: string, // 配置标题
  description: string, // 配置描述
  sort: number, // 排序
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface ISystemAdminCreateRequest {
  id: number, // ID
  username: string, // 用户名
  password: string, // 登录密码
  name: string, // 昵称
  avatar: string, // 头像
  mobile: string, // 手机号码
  email: string, // 电子邮箱
  status: number, // 状态: 1-正常;2-禁用
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface ISystemAdminQueryRequest {
}

export interface ISystemAdminUpdateRequest {
  id: number, // ID
  username: string, // 用户名
  password: string, // 登录密码
  name: string, // 昵称
  avatar: string, // 头像
  mobile: string, // 手机号码
  email: string, // 电子邮箱
  status: number, // 状态: 1-正常;2-禁用
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface ISystemAdminRoleCreateRequest {
  id: number, // ID
  system_admin_id: number, // 管理员ID
  system_role_id: number, // 角色ID
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISystemAdminRoleQueryRequest {
}

export interface ISystemAdminRoleUpdateRequest {
  id: number, // ID
  system_admin_id: number, // 管理员ID
  system_role_id: number, // 角色ID
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISystemMenuCreateRequest {
  id: number, // ID
  parent_id: number, // 上级菜单ID
  name: string, // 名称
  icon: string, // ICON图标
  description: string, // 描述
  sort: number, // 排序
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISystemMenuQueryRequest {
}

export interface ISystemMenuUpdateRequest {
  id: number, // ID
  parent_id: number, // 上级菜单ID
  name: string, // 名称
  icon: string, // ICON图标
  description: string, // 描述
  sort: number, // 排序
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISystemPermissionCreateRequest {
  id: number, // ID
  code: string, // 资源码
  name: string, // 资源名称
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISystemPermissionQueryRequest {
}

export interface ISystemPermissionUpdateRequest {
  id: number, // ID
  code: string, // 资源码
  name: string, // 资源名称
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISystemRoleCreateRequest {
  id: number, // ID
  code: string, // 角色码
  name: string, // 角色名称
  description: string, // 角色描述
  status: number, // 状态: 1-正常;2-禁用
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface ISystemRoleQueryRequest {
}

export interface ISystemRoleUpdateRequest {
  id: number, // ID
  code: string, // 角色码
  name: string, // 角色名称
  description: string, // 角色描述
  status: number, // 状态: 1-正常;2-禁用
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface ISystemRolePermissionCreateRequest {
  id: number, // ID
  system_role_id: number, // 角色ID
  system_permission_id: number, // 资源ID
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISystemRolePermissionQueryRequest {
}

export interface ISystemRolePermissionUpdateRequest {
  id: number, // ID
  system_role_id: number, // 角色ID
  system_permission_id: number, // 资源ID
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ISystemAdminQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ISystemAdminResponse[], // 数据列表
}

export interface ISystemAdminResponse {
  id: number, // ID
  username: string, // 用户名
  password: string, // 登录密码
  name: string, // 昵称
  avatar: string, // 头像
  mobile: string, // 手机号码
  email: string, // 电子邮箱
  status: number, // 状态: 1-正常;2-禁用
  deleted: number, // 删除状态: 0-未删除;1-已删除
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
  deletedTime: string, // 删除时间
}

export interface ISystemAdminRoleQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ISystemAdminRoleResponse[], // 数据列表
}

export interface ISystemAdminRoleResponse {
  id: number, // ID
  systemAdminId: number, // 管理员ID
  systemRoleId: number, // 角色ID
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface ISystemMenuQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ISystemMenuResponse[], // 数据列表
}

export interface ISystemMenuResponse {
  id: number, // ID
  parentId: number, // 上级菜单ID
  name: string, // 名称
  icon: string, // ICON图标
  description: string, // 描述
  sort: number, // 排序
  status: number, // 状态: 1-正常;2-禁用
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface ISystemPermissionQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ISystemPermissionResponse[], // 数据列表
}

export interface ISystemPermissionResponse {
  id: number, // ID
  code: string, // 资源码
  name: string, // 资源名称
  status: number, // 状态: 1-正常;2-禁用
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface ISystemRoleQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ISystemRoleResponse[], // 数据列表
}

export interface ISystemRoleResponse {
  id: number, // ID
  code: string, // 角色码
  name: string, // 角色名称
  description: string, // 角色描述
  status: number, // 状态: 1-正常;2-禁用
  deleted: number, // 删除状态: 0-未删除;1-已删除
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
  deletedTime: string, // 删除时间
}

export interface ISystemRolePermissionQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ISystemRolePermissionResponse[], // 数据列表
}

export interface ISystemRolePermissionResponse {
  id: number, // ID
  systemRoleId: number, // 角色ID
  systemPermissionId: number, // 资源ID
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface ITagCreateRequest {
  id: number, // ID
  name: string, // 标签名称
  slug: string, // 标签别名
  description: string, // 标签描述
  use_count: number, // 使用次数
  color: string, // 标签颜色
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ITagQueryRequest {
}

export interface ITagUpdateRequest {
  id: number, // ID
  name: string, // 标签名称
  slug: string, // 标签别名
  description: string, // 标签描述
  use_count: number, // 使用次数
  color: string, // 标签颜色
  status: number, // 状态: 1-正常;2-禁用
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
}

export interface ITagQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: ITagResponse[], // 数据列表
}

export interface ITagResponse {
  id: number, // ID
  name: string, // 标签名称
  slug: string, // 标签别名
  description: string, // 标签描述
  useCount: number, // 使用次数
  color: string, // 标签颜色
  status: number, // 状态: 1-正常;2-禁用
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
}

export interface IUserCreateRequest {
  id: number, // ID
  username: string, // 用户名
  password: string, // 登录密码
  nickname: string, // 昵称
  real_name: string, // 真实姓名
  avatar: string, // 头像
  mobile: string, // 手机号码
  email: string, // 电子邮箱
  gender: number, // 性别: 0-未知;1-男;2-女
  birthday: string, // 生日
  province: string, // 省份
  city: string, // 城市
  district: string, // 区县
  address: string, // 详细地址
  id_card: string, // 身份证号
  bio: string, // 个人简介
  points: number, // 积分
  balance: number, // 余额
  level: number, // 用户等级
  status: number, // 状态: 1-正常;2-禁用;3-冻结
  register_ip: string, // 注册IP
  last_login_ip: string, // 最后登录IP
  last_login_time: string, // 最后登录时间
  is_verified: number, // 是否实名认证: 0-否;1-是
  email_verified: number, // 邮箱是否验证: 0-否;1-是
  mobile_verified: number, // 手机是否验证: 0-否;1-是
  openid: string, // 微信OpenID
  unionid: string, // 微信UnionID
  extra_data: string, // 扩展数据(JSON)
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface IUserQueryRequest {
}

export interface IUserUpdateRequest {
  id: number, // ID
  username: string, // 用户名
  password: string, // 登录密码
  nickname: string, // 昵称
  real_name: string, // 真实姓名
  avatar: string, // 头像
  mobile: string, // 手机号码
  email: string, // 电子邮箱
  gender: number, // 性别: 0-未知;1-男;2-女
  birthday: string, // 生日
  province: string, // 省份
  city: string, // 城市
  district: string, // 区县
  address: string, // 详细地址
  id_card: string, // 身份证号
  bio: string, // 个人简介
  points: number, // 积分
  balance: number, // 余额
  level: number, // 用户等级
  status: number, // 状态: 1-正常;2-禁用;3-冻结
  register_ip: string, // 注册IP
  last_login_ip: string, // 最后登录IP
  last_login_time: string, // 最后登录时间
  is_verified: number, // 是否实名认证: 0-否;1-是
  email_verified: number, // 邮箱是否验证: 0-否;1-是
  mobile_verified: number, // 手机是否验证: 0-否;1-是
  openid: string, // 微信OpenID
  unionid: string, // 微信UnionID
  extra_data: string, // 扩展数据(JSON)
  deleted: number, // 删除状态: 0-未删除;1-已删除
  created_time: string, // 创建时间
  updated_time: string, // 更新时间
  deleted_time: string, // 删除时间
}

export interface IUserQueryResponse {
  total: number, // 数据总数
  per_page: number, // 每页数据量
  current_page: number, // 当前页码
  last_page: number, // 最后页面
  data: IUserResponse[], // 数据列表
}

export interface IUserResponse {
  id: number, // ID
  username: string, // 用户名
  password: string, // 登录密码
  nickname: string, // 昵称
  realName: string, // 真实姓名
  avatar: string, // 头像
  mobile: string, // 手机号码
  email: string, // 电子邮箱
  gender: number, // 性别: 0-未知;1-男;2-女
  birthday: string, // 生日
  province: string, // 省份
  city: string, // 城市
  district: string, // 区县
  address: string, // 详细地址
  idCard: string, // 身份证号
  bio: string, // 个人简介
  points: number, // 积分
  balance: number, // 余额
  level: number, // 用户等级
  status: number, // 状态: 1-正常;2-禁用;3-冻结
  registerIp: string, // 注册IP
  lastLoginIp: string, // 最后登录IP
  lastLoginTime: string, // 最后登录时间
  isVerified: number, // 是否实名认证: 0-否;1-是
  emailVerified: number, // 邮箱是否验证: 0-否;1-是
  mobileVerified: number, // 手机是否验证: 0-否;1-是
  openid: string, // 微信OpenID
  unionid: string, // 微信UnionID
  extraData: string, // 扩展数据(JSON)
  deleted: number, // 删除状态: 0-未删除;1-已删除
  createdTime: string, // 创建时间
  updatedTime: string, // 更新时间
  deletedTime: string, // 删除时间
}


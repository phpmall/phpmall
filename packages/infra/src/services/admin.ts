import request from '@/utils/request'
import type { ILoginResponse,
IArticleQueryRequest,
IArticleQueryResponse,
IArticleCreateRequest,
IArticleResponse,
IArticleUpdateRequest,
IArticleTagQueryRequest,
IArticleTagQueryResponse,
IArticleTagCreateRequest,
IArticleTagResponse,
IArticleTagUpdateRequest,
IAttachmentCategoryQueryRequest,
IAttachmentCategoryQueryResponse,
IAttachmentCategoryCreateRequest,
IAttachmentCategoryResponse,
IAttachmentCategoryUpdateRequest,
IAttachmentQueryRequest,
IAttachmentQueryResponse,
IAttachmentCreateRequest,
IAttachmentResponse,
IAttachmentUpdateRequest,
IBannerQueryRequest,
IBannerQueryResponse,
IBannerCreateRequest,
IBannerResponse,
IBannerUpdateRequest,
IBannerPositionQueryRequest,
IBannerPositionQueryResponse,
IBannerPositionCreateRequest,
IBannerPositionResponse,
IBannerPositionUpdateRequest,
ICategoryQueryRequest,
ICategoryQueryResponse,
ICategoryCreateRequest,
ICategoryResponse,
ICategoryUpdateRequest,
ICommentQueryRequest,
ICommentQueryResponse,
ICommentCreateRequest,
ICommentResponse,
ICommentUpdateRequest,
IDictQueryRequest,
IDictQueryResponse,
IDictCreateRequest,
IDictResponse,
IDictUpdateRequest,
IFavoriteQueryRequest,
IFavoriteQueryResponse,
IFavoriteCreateRequest,
IFavoriteResponse,
IFavoriteUpdateRequest,
ILikeQueryRequest,
ILikeQueryResponse,
ILikeCreateRequest,
ILikeResponse,
ILikeUpdateRequest,
ILinkQueryRequest,
ILinkQueryResponse,
ILinkCreateRequest,
ILinkResponse,
ILinkUpdateRequest,
ILoginLogQueryRequest,
ILoginLogQueryResponse,
ILoginLogCreateRequest,
ILoginLogResponse,
ILoginLogUpdateRequest,
INavigationQueryRequest,
INavigationQueryResponse,
INavigationCreateRequest,
INavigationResponse,
INavigationUpdateRequest,
INotificationQueryRequest,
INotificationQueryResponse,
INotificationCreateRequest,
INotificationResponse,
INotificationUpdateRequest,
IOperationLogQueryRequest,
IOperationLogQueryResponse,
IOperationLogCreateRequest,
IOperationLogResponse,
IOperationLogUpdateRequest,
IPageQueryRequest,
IPageQueryResponse,
IPageCreateRequest,
IPageResponse,
IPageUpdateRequest,
ISettingQueryRequest,
ISettingQueryResponse,
ISettingCreateRequest,
ISettingResponse,
ISettingUpdateRequest,
ISystemAdminQueryRequest,
ISystemAdminQueryResponse,
ISystemAdminCreateRequest,
ISystemAdminResponse,
ISystemAdminUpdateRequest,
ISystemAdminRoleQueryRequest,
ISystemAdminRoleQueryResponse,
ISystemAdminRoleCreateRequest,
ISystemAdminRoleResponse,
ISystemAdminRoleUpdateRequest,
ISystemMenuQueryRequest,
ISystemMenuQueryResponse,
ISystemMenuCreateRequest,
ISystemMenuResponse,
ISystemMenuUpdateRequest,
ISystemPermissionQueryRequest,
ISystemPermissionQueryResponse,
ISystemPermissionCreateRequest,
ISystemPermissionResponse,
ISystemPermissionUpdateRequest,
ISystemRoleQueryRequest,
ISystemRoleQueryResponse,
ISystemRoleCreateRequest,
ISystemRoleResponse,
ISystemRoleUpdateRequest,
ISystemRolePermissionQueryRequest,
ISystemRolePermissionQueryResponse,
ISystemRolePermissionCreateRequest,
ISystemRolePermissionResponse,
ISystemRolePermissionUpdateRequest,
ITagQueryRequest,
ITagQueryResponse,
ITagCreateRequest,
ITagResponse,
ITagUpdateRequest,
IUserQueryRequest,
IUserQueryResponse,
IUserCreateRequest,
IUserResponse,
IUserUpdateRequest } from '@/types/admin.d'

// [认证模块] 用户登录接口
export const loginService = (): Promise<ILoginResponse> => {
    return request({
        url: '/login',
        method: 'post'
    })
}

// [认证模块] 用户忘记密码
export const forgetService = (): Promise<any> => {
    return request({
        url: '/forget',
        method: 'post'
    })
}

// [认证模块] 用户重设密码
export const resetService = (): Promise<any> => {
    return request({
        url: '/reset',
        method: 'post'
    })
}

// [文章模块] 查询列表接口
export const articleQueryService = (page: number, pageSize: number, formData: IArticleQueryRequest): Promise<IArticleQueryResponse> => {
    return request({
        url: '/article/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [文章模块] 新增接口
export const articleCreateService = (formData: IArticleCreateRequest): Promise<any> => {
    return request({
        url: '/article/create',
        method: 'post',
        data: formData
    })
}

// [文章模块] 获取详情接口
export const articleShowService = (id: number): Promise<IArticleResponse> => {
    return request({
        url: '/article/show',
        method: 'get',
        params: {id}
    })
}

// [文章模块] 更新接口
export const articleUpdateService = (formData: IArticleUpdateRequest): Promise<any> => {
    return request({
        url: '/article/update',
        method: 'put',
        data: formData
    })
}

// [文章模块] 删除接口
export const articleDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/article/destroy',
        method: 'delete',
        params: {id}
    })
}

// [文章标签关联模块] 查询列表接口
export const articleTagQueryService = (page: number, pageSize: number, formData: IArticleTagQueryRequest): Promise<IArticleTagQueryResponse> => {
    return request({
        url: '/articleTag/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [文章标签关联模块] 新增接口
export const articleTagCreateService = (formData: IArticleTagCreateRequest): Promise<any> => {
    return request({
        url: '/articleTag/create',
        method: 'post',
        data: formData
    })
}

// [文章标签关联模块] 获取详情接口
export const articleTagShowService = (id: number): Promise<IArticleTagResponse> => {
    return request({
        url: '/articleTag/show',
        method: 'get',
        params: {id}
    })
}

// [文章标签关联模块] 更新接口
export const articleTagUpdateService = (formData: IArticleTagUpdateRequest): Promise<any> => {
    return request({
        url: '/articleTag/update',
        method: 'put',
        data: formData
    })
}

// [文章标签关联模块] 删除接口
export const articleTagDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/articleTag/destroy',
        method: 'delete',
        params: {id}
    })
}

// [附件分类模块] 查询列表接口
export const attachmentCategoryQueryService = (page: number, pageSize: number, formData: IAttachmentCategoryQueryRequest): Promise<IAttachmentCategoryQueryResponse> => {
    return request({
        url: '/attachmentCategory/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [附件分类模块] 新增接口
export const attachmentCategoryCreateService = (formData: IAttachmentCategoryCreateRequest): Promise<any> => {
    return request({
        url: '/attachmentCategory/create',
        method: 'post',
        data: formData
    })
}

// [附件分类模块] 获取详情接口
export const attachmentCategoryShowService = (id: number): Promise<IAttachmentCategoryResponse> => {
    return request({
        url: '/attachmentCategory/show',
        method: 'get',
        params: {id}
    })
}

// [附件分类模块] 更新接口
export const attachmentCategoryUpdateService = (formData: IAttachmentCategoryUpdateRequest): Promise<any> => {
    return request({
        url: '/attachmentCategory/update',
        method: 'put',
        data: formData
    })
}

// [附件分类模块] 删除接口
export const attachmentCategoryDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/attachmentCategory/destroy',
        method: 'delete',
        params: {id}
    })
}

// [附件模块] 查询列表接口
export const attachmentQueryService = (page: number, pageSize: number, formData: IAttachmentQueryRequest): Promise<IAttachmentQueryResponse> => {
    return request({
        url: '/attachment/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [附件模块] 新增接口
export const attachmentCreateService = (formData: IAttachmentCreateRequest): Promise<any> => {
    return request({
        url: '/attachment/create',
        method: 'post',
        data: formData
    })
}

// [附件模块] 获取详情接口
export const attachmentShowService = (id: number): Promise<IAttachmentResponse> => {
    return request({
        url: '/attachment/show',
        method: 'get',
        params: {id}
    })
}

// [附件模块] 更新接口
export const attachmentUpdateService = (formData: IAttachmentUpdateRequest): Promise<any> => {
    return request({
        url: '/attachment/update',
        method: 'put',
        data: formData
    })
}

// [附件模块] 删除接口
export const attachmentDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/attachment/destroy',
        method: 'delete',
        params: {id}
    })
}

// [广告/轮播模块] 查询列表接口
export const bannerQueryService = (page: number, pageSize: number, formData: IBannerQueryRequest): Promise<IBannerQueryResponse> => {
    return request({
        url: '/banner/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [广告/轮播模块] 新增接口
export const bannerCreateService = (formData: IBannerCreateRequest): Promise<any> => {
    return request({
        url: '/banner/create',
        method: 'post',
        data: formData
    })
}

// [广告/轮播模块] 获取详情接口
export const bannerShowService = (id: number): Promise<IBannerResponse> => {
    return request({
        url: '/banner/show',
        method: 'get',
        params: {id}
    })
}

// [广告/轮播模块] 更新接口
export const bannerUpdateService = (formData: IBannerUpdateRequest): Promise<any> => {
    return request({
        url: '/banner/update',
        method: 'put',
        data: formData
    })
}

// [广告/轮播模块] 删除接口
export const bannerDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/banner/destroy',
        method: 'delete',
        params: {id}
    })
}

// [广告位模块] 查询列表接口
export const bannerPositionQueryService = (page: number, pageSize: number, formData: IBannerPositionQueryRequest): Promise<IBannerPositionQueryResponse> => {
    return request({
        url: '/bannerPosition/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [广告位模块] 新增接口
export const bannerPositionCreateService = (formData: IBannerPositionCreateRequest): Promise<any> => {
    return request({
        url: '/bannerPosition/create',
        method: 'post',
        data: formData
    })
}

// [广告位模块] 获取详情接口
export const bannerPositionShowService = (id: number): Promise<IBannerPositionResponse> => {
    return request({
        url: '/bannerPosition/show',
        method: 'get',
        params: {id}
    })
}

// [广告位模块] 更新接口
export const bannerPositionUpdateService = (formData: IBannerPositionUpdateRequest): Promise<any> => {
    return request({
        url: '/bannerPosition/update',
        method: 'put',
        data: formData
    })
}

// [广告位模块] 删除接口
export const bannerPositionDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/bannerPosition/destroy',
        method: 'delete',
        params: {id}
    })
}

// [内容分类模块] 查询列表接口
export const categoryQueryService = (page: number, pageSize: number, formData: ICategoryQueryRequest): Promise<ICategoryQueryResponse> => {
    return request({
        url: '/category/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [内容分类模块] 新增接口
export const categoryCreateService = (formData: ICategoryCreateRequest): Promise<any> => {
    return request({
        url: '/category/create',
        method: 'post',
        data: formData
    })
}

// [内容分类模块] 获取详情接口
export const categoryShowService = (id: number): Promise<ICategoryResponse> => {
    return request({
        url: '/category/show',
        method: 'get',
        params: {id}
    })
}

// [内容分类模块] 更新接口
export const categoryUpdateService = (formData: ICategoryUpdateRequest): Promise<any> => {
    return request({
        url: '/category/update',
        method: 'put',
        data: formData
    })
}

// [内容分类模块] 删除接口
export const categoryDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/category/destroy',
        method: 'delete',
        params: {id}
    })
}

// [评论模块] 查询列表接口
export const commentQueryService = (page: number, pageSize: number, formData: ICommentQueryRequest): Promise<ICommentQueryResponse> => {
    return request({
        url: '/comment/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [评论模块] 新增接口
export const commentCreateService = (formData: ICommentCreateRequest): Promise<any> => {
    return request({
        url: '/comment/create',
        method: 'post',
        data: formData
    })
}

// [评论模块] 获取详情接口
export const commentShowService = (id: number): Promise<ICommentResponse> => {
    return request({
        url: '/comment/show',
        method: 'get',
        params: {id}
    })
}

// [评论模块] 更新接口
export const commentUpdateService = (formData: ICommentUpdateRequest): Promise<any> => {
    return request({
        url: '/comment/update',
        method: 'put',
        data: formData
    })
}

// [评论模块] 删除接口
export const commentDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/comment/destroy',
        method: 'delete',
        params: {id}
    })
}

// [字典模块] 查询列表接口
export const dictQueryService = (page: number, pageSize: number, formData: IDictQueryRequest): Promise<IDictQueryResponse> => {
    return request({
        url: '/dict/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [字典模块] 新增接口
export const dictCreateService = (formData: IDictCreateRequest): Promise<any> => {
    return request({
        url: '/dict/create',
        method: 'post',
        data: formData
    })
}

// [字典模块] 获取详情接口
export const dictShowService = (id: number): Promise<IDictResponse> => {
    return request({
        url: '/dict/show',
        method: 'get',
        params: {id}
    })
}

// [字典模块] 更新接口
export const dictUpdateService = (formData: IDictUpdateRequest): Promise<any> => {
    return request({
        url: '/dict/update',
        method: 'put',
        data: formData
    })
}

// [字典模块] 删除接口
export const dictDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/dict/destroy',
        method: 'delete',
        params: {id}
    })
}

// [收藏模块] 查询列表接口
export const favoriteQueryService = (page: number, pageSize: number, formData: IFavoriteQueryRequest): Promise<IFavoriteQueryResponse> => {
    return request({
        url: '/favorite/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [收藏模块] 新增接口
export const favoriteCreateService = (formData: IFavoriteCreateRequest): Promise<any> => {
    return request({
        url: '/favorite/create',
        method: 'post',
        data: formData
    })
}

// [收藏模块] 获取详情接口
export const favoriteShowService = (id: number): Promise<IFavoriteResponse> => {
    return request({
        url: '/favorite/show',
        method: 'get',
        params: {id}
    })
}

// [收藏模块] 更新接口
export const favoriteUpdateService = (formData: IFavoriteUpdateRequest): Promise<any> => {
    return request({
        url: '/favorite/update',
        method: 'put',
        data: formData
    })
}

// [收藏模块] 删除接口
export const favoriteDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/favorite/destroy',
        method: 'delete',
        params: {id}
    })
}

// [点赞模块] 查询列表接口
export const likeQueryService = (page: number, pageSize: number, formData: ILikeQueryRequest): Promise<ILikeQueryResponse> => {
    return request({
        url: '/like/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [点赞模块] 新增接口
export const likeCreateService = (formData: ILikeCreateRequest): Promise<any> => {
    return request({
        url: '/like/create',
        method: 'post',
        data: formData
    })
}

// [点赞模块] 获取详情接口
export const likeShowService = (id: number): Promise<ILikeResponse> => {
    return request({
        url: '/like/show',
        method: 'get',
        params: {id}
    })
}

// [点赞模块] 更新接口
export const likeUpdateService = (formData: ILikeUpdateRequest): Promise<any> => {
    return request({
        url: '/like/update',
        method: 'put',
        data: formData
    })
}

// [点赞模块] 删除接口
export const likeDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/like/destroy',
        method: 'delete',
        params: {id}
    })
}

// [友情链接模块] 查询列表接口
export const linkQueryService = (page: number, pageSize: number, formData: ILinkQueryRequest): Promise<ILinkQueryResponse> => {
    return request({
        url: '/link/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [友情链接模块] 新增接口
export const linkCreateService = (formData: ILinkCreateRequest): Promise<any> => {
    return request({
        url: '/link/create',
        method: 'post',
        data: formData
    })
}

// [友情链接模块] 获取详情接口
export const linkShowService = (id: number): Promise<ILinkResponse> => {
    return request({
        url: '/link/show',
        method: 'get',
        params: {id}
    })
}

// [友情链接模块] 更新接口
export const linkUpdateService = (formData: ILinkUpdateRequest): Promise<any> => {
    return request({
        url: '/link/update',
        method: 'put',
        data: formData
    })
}

// [友情链接模块] 删除接口
export const linkDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/link/destroy',
        method: 'delete',
        params: {id}
    })
}

// [登录日志模块] 查询列表接口
export const loginLogQueryService = (page: number, pageSize: number, formData: ILoginLogQueryRequest): Promise<ILoginLogQueryResponse> => {
    return request({
        url: '/loginLog/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [登录日志模块] 新增接口
export const loginLogCreateService = (formData: ILoginLogCreateRequest): Promise<any> => {
    return request({
        url: '/loginLog/create',
        method: 'post',
        data: formData
    })
}

// [登录日志模块] 获取详情接口
export const loginLogShowService = (id: number): Promise<ILoginLogResponse> => {
    return request({
        url: '/loginLog/show',
        method: 'get',
        params: {id}
    })
}

// [登录日志模块] 更新接口
export const loginLogUpdateService = (formData: ILoginLogUpdateRequest): Promise<any> => {
    return request({
        url: '/loginLog/update',
        method: 'put',
        data: formData
    })
}

// [登录日志模块] 删除接口
export const loginLogDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/loginLog/destroy',
        method: 'delete',
        params: {id}
    })
}

// [导航模块] 查询列表接口
export const navigationQueryService = (page: number, pageSize: number, formData: INavigationQueryRequest): Promise<INavigationQueryResponse> => {
    return request({
        url: '/navigation/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [导航模块] 新增接口
export const navigationCreateService = (formData: INavigationCreateRequest): Promise<any> => {
    return request({
        url: '/navigation/create',
        method: 'post',
        data: formData
    })
}

// [导航模块] 获取详情接口
export const navigationShowService = (id: number): Promise<INavigationResponse> => {
    return request({
        url: '/navigation/show',
        method: 'get',
        params: {id}
    })
}

// [导航模块] 更新接口
export const navigationUpdateService = (formData: INavigationUpdateRequest): Promise<any> => {
    return request({
        url: '/navigation/update',
        method: 'put',
        data: formData
    })
}

// [导航模块] 删除接口
export const navigationDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/navigation/destroy',
        method: 'delete',
        params: {id}
    })
}

// [通知模块] 查询列表接口
export const notificationQueryService = (page: number, pageSize: number, formData: INotificationQueryRequest): Promise<INotificationQueryResponse> => {
    return request({
        url: '/notification/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [通知模块] 新增接口
export const notificationCreateService = (formData: INotificationCreateRequest): Promise<any> => {
    return request({
        url: '/notification/create',
        method: 'post',
        data: formData
    })
}

// [通知模块] 获取详情接口
export const notificationShowService = (id: number): Promise<INotificationResponse> => {
    return request({
        url: '/notification/show',
        method: 'get',
        params: {id}
    })
}

// [通知模块] 更新接口
export const notificationUpdateService = (formData: INotificationUpdateRequest): Promise<any> => {
    return request({
        url: '/notification/update',
        method: 'put',
        data: formData
    })
}

// [通知模块] 删除接口
export const notificationDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/notification/destroy',
        method: 'delete',
        params: {id}
    })
}

// [操作日志模块] 查询列表接口
export const operationLogQueryService = (page: number, pageSize: number, formData: IOperationLogQueryRequest): Promise<IOperationLogQueryResponse> => {
    return request({
        url: '/operationLog/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [操作日志模块] 新增接口
export const operationLogCreateService = (formData: IOperationLogCreateRequest): Promise<any> => {
    return request({
        url: '/operationLog/create',
        method: 'post',
        data: formData
    })
}

// [操作日志模块] 获取详情接口
export const operationLogShowService = (id: number): Promise<IOperationLogResponse> => {
    return request({
        url: '/operationLog/show',
        method: 'get',
        params: {id}
    })
}

// [操作日志模块] 更新接口
export const operationLogUpdateService = (formData: IOperationLogUpdateRequest): Promise<any> => {
    return request({
        url: '/operationLog/update',
        method: 'put',
        data: formData
    })
}

// [操作日志模块] 删除接口
export const operationLogDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/operationLog/destroy',
        method: 'delete',
        params: {id}
    })
}

// [单页模块] 查询列表接口
export const pageQueryService = (page: number, pageSize: number, formData: IPageQueryRequest): Promise<IPageQueryResponse> => {
    return request({
        url: '/page/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [单页模块] 新增接口
export const pageCreateService = (formData: IPageCreateRequest): Promise<any> => {
    return request({
        url: '/page/create',
        method: 'post',
        data: formData
    })
}

// [单页模块] 获取详情接口
export const pageShowService = (id: number): Promise<IPageResponse> => {
    return request({
        url: '/page/show',
        method: 'get',
        params: {id}
    })
}

// [单页模块] 更新接口
export const pageUpdateService = (formData: IPageUpdateRequest): Promise<any> => {
    return request({
        url: '/page/update',
        method: 'put',
        data: formData
    })
}

// [单页模块] 删除接口
export const pageDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/page/destroy',
        method: 'delete',
        params: {id}
    })
}

// [系统配置模块] 查询列表接口
export const settingQueryService = (page: number, pageSize: number, formData: ISettingQueryRequest): Promise<ISettingQueryResponse> => {
    return request({
        url: '/setting/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [系统配置模块] 新增接口
export const settingCreateService = (formData: ISettingCreateRequest): Promise<any> => {
    return request({
        url: '/setting/create',
        method: 'post',
        data: formData
    })
}

// [系统配置模块] 获取详情接口
export const settingShowService = (id: number): Promise<ISettingResponse> => {
    return request({
        url: '/setting/show',
        method: 'get',
        params: {id}
    })
}

// [系统配置模块] 更新接口
export const settingUpdateService = (formData: ISettingUpdateRequest): Promise<any> => {
    return request({
        url: '/setting/update',
        method: 'put',
        data: formData
    })
}

// [系统配置模块] 删除接口
export const settingDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/setting/destroy',
        method: 'delete',
        params: {id}
    })
}

// [系统管理员模块] 查询列表接口
export const systemAdminQueryService = (page: number, pageSize: number, formData: ISystemAdminQueryRequest): Promise<ISystemAdminQueryResponse> => {
    return request({
        url: '/systemAdmin/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [系统管理员模块] 新增接口
export const systemAdminCreateService = (formData: ISystemAdminCreateRequest): Promise<any> => {
    return request({
        url: '/systemAdmin/create',
        method: 'post',
        data: formData
    })
}

// [系统管理员模块] 获取详情接口
export const systemAdminShowService = (id: number): Promise<ISystemAdminResponse> => {
    return request({
        url: '/systemAdmin/show',
        method: 'get',
        params: {id}
    })
}

// [系统管理员模块] 更新接口
export const systemAdminUpdateService = (formData: ISystemAdminUpdateRequest): Promise<any> => {
    return request({
        url: '/systemAdmin/update',
        method: 'put',
        data: formData
    })
}

// [系统管理员模块] 删除接口
export const systemAdminDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/systemAdmin/destroy',
        method: 'delete',
        params: {id}
    })
}

// [系统管理员角色关系模块] 查询列表接口
export const systemAdminRoleQueryService = (page: number, pageSize: number, formData: ISystemAdminRoleQueryRequest): Promise<ISystemAdminRoleQueryResponse> => {
    return request({
        url: '/systemAdminRole/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [系统管理员角色关系模块] 新增接口
export const systemAdminRoleCreateService = (formData: ISystemAdminRoleCreateRequest): Promise<any> => {
    return request({
        url: '/systemAdminRole/create',
        method: 'post',
        data: formData
    })
}

// [系统管理员角色关系模块] 获取详情接口
export const systemAdminRoleShowService = (id: number): Promise<ISystemAdminRoleResponse> => {
    return request({
        url: '/systemAdminRole/show',
        method: 'get',
        params: {id}
    })
}

// [系统管理员角色关系模块] 更新接口
export const systemAdminRoleUpdateService = (formData: ISystemAdminRoleUpdateRequest): Promise<any> => {
    return request({
        url: '/systemAdminRole/update',
        method: 'put',
        data: formData
    })
}

// [系统管理员角色关系模块] 删除接口
export const systemAdminRoleDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/systemAdminRole/destroy',
        method: 'delete',
        params: {id}
    })
}

// [系统菜单模块] 查询列表接口
export const systemMenuQueryService = (page: number, pageSize: number, formData: ISystemMenuQueryRequest): Promise<ISystemMenuQueryResponse> => {
    return request({
        url: '/systemMenu/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [系统菜单模块] 新增接口
export const systemMenuCreateService = (formData: ISystemMenuCreateRequest): Promise<any> => {
    return request({
        url: '/systemMenu/create',
        method: 'post',
        data: formData
    })
}

// [系统菜单模块] 获取详情接口
export const systemMenuShowService = (id: number): Promise<ISystemMenuResponse> => {
    return request({
        url: '/systemMenu/show',
        method: 'get',
        params: {id}
    })
}

// [系统菜单模块] 更新接口
export const systemMenuUpdateService = (formData: ISystemMenuUpdateRequest): Promise<any> => {
    return request({
        url: '/systemMenu/update',
        method: 'put',
        data: formData
    })
}

// [系统菜单模块] 删除接口
export const systemMenuDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/systemMenu/destroy',
        method: 'delete',
        params: {id}
    })
}

// [系统权限模块] 查询列表接口
export const systemPermissionQueryService = (page: number, pageSize: number, formData: ISystemPermissionQueryRequest): Promise<ISystemPermissionQueryResponse> => {
    return request({
        url: '/systemPermission/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [系统权限模块] 新增接口
export const systemPermissionCreateService = (formData: ISystemPermissionCreateRequest): Promise<any> => {
    return request({
        url: '/systemPermission/create',
        method: 'post',
        data: formData
    })
}

// [系统权限模块] 获取详情接口
export const systemPermissionShowService = (id: number): Promise<ISystemPermissionResponse> => {
    return request({
        url: '/systemPermission/show',
        method: 'get',
        params: {id}
    })
}

// [系统权限模块] 更新接口
export const systemPermissionUpdateService = (formData: ISystemPermissionUpdateRequest): Promise<any> => {
    return request({
        url: '/systemPermission/update',
        method: 'put',
        data: formData
    })
}

// [系统权限模块] 删除接口
export const systemPermissionDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/systemPermission/destroy',
        method: 'delete',
        params: {id}
    })
}

// [系统角色模块] 查询列表接口
export const systemRoleQueryService = (page: number, pageSize: number, formData: ISystemRoleQueryRequest): Promise<ISystemRoleQueryResponse> => {
    return request({
        url: '/systemRole/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [系统角色模块] 新增接口
export const systemRoleCreateService = (formData: ISystemRoleCreateRequest): Promise<any> => {
    return request({
        url: '/systemRole/create',
        method: 'post',
        data: formData
    })
}

// [系统角色模块] 获取详情接口
export const systemRoleShowService = (id: number): Promise<ISystemRoleResponse> => {
    return request({
        url: '/systemRole/show',
        method: 'get',
        params: {id}
    })
}

// [系统角色模块] 更新接口
export const systemRoleUpdateService = (formData: ISystemRoleUpdateRequest): Promise<any> => {
    return request({
        url: '/systemRole/update',
        method: 'put',
        data: formData
    })
}

// [系统角色模块] 删除接口
export const systemRoleDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/systemRole/destroy',
        method: 'delete',
        params: {id}
    })
}

// [系统角色权限关系模块] 查询列表接口
export const systemRolePermissionQueryService = (page: number, pageSize: number, formData: ISystemRolePermissionQueryRequest): Promise<ISystemRolePermissionQueryResponse> => {
    return request({
        url: '/systemRolePermission/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [系统角色权限关系模块] 新增接口
export const systemRolePermissionCreateService = (formData: ISystemRolePermissionCreateRequest): Promise<any> => {
    return request({
        url: '/systemRolePermission/create',
        method: 'post',
        data: formData
    })
}

// [系统角色权限关系模块] 获取详情接口
export const systemRolePermissionShowService = (id: number): Promise<ISystemRolePermissionResponse> => {
    return request({
        url: '/systemRolePermission/show',
        method: 'get',
        params: {id}
    })
}

// [系统角色权限关系模块] 更新接口
export const systemRolePermissionUpdateService = (formData: ISystemRolePermissionUpdateRequest): Promise<any> => {
    return request({
        url: '/systemRolePermission/update',
        method: 'put',
        data: formData
    })
}

// [系统角色权限关系模块] 删除接口
export const systemRolePermissionDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/systemRolePermission/destroy',
        method: 'delete',
        params: {id}
    })
}

// [标签模块] 查询列表接口
export const tagQueryService = (page: number, pageSize: number, formData: ITagQueryRequest): Promise<ITagQueryResponse> => {
    return request({
        url: '/tag/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [标签模块] 新增接口
export const tagCreateService = (formData: ITagCreateRequest): Promise<any> => {
    return request({
        url: '/tag/create',
        method: 'post',
        data: formData
    })
}

// [标签模块] 获取详情接口
export const tagShowService = (id: number): Promise<ITagResponse> => {
    return request({
        url: '/tag/show',
        method: 'get',
        params: {id}
    })
}

// [标签模块] 更新接口
export const tagUpdateService = (formData: ITagUpdateRequest): Promise<any> => {
    return request({
        url: '/tag/update',
        method: 'put',
        data: formData
    })
}

// [标签模块] 删除接口
export const tagDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/tag/destroy',
        method: 'delete',
        params: {id}
    })
}

// [用户模块] 查询列表接口
export const userQueryService = (page: number, pageSize: number, formData: IUserQueryRequest): Promise<IUserQueryResponse> => {
    return request({
        url: '/user/query',
        method: 'post',
        params: {page, pageSize},
        data: formData
    })
}

// [用户模块] 新增接口
export const userCreateService = (formData: IUserCreateRequest): Promise<any> => {
    return request({
        url: '/user/create',
        method: 'post',
        data: formData
    })
}

// [用户模块] 获取详情接口
export const userShowService = (id: number): Promise<IUserResponse> => {
    return request({
        url: '/user/show',
        method: 'get',
        params: {id}
    })
}

// [用户模块] 更新接口
export const userUpdateService = (formData: IUserUpdateRequest): Promise<any> => {
    return request({
        url: '/user/update',
        method: 'put',
        data: formData
    })
}

// [用户模块] 删除接口
export const userDestroyService = (id: number): Promise<any> => {
    return request({
        url: '/user/destroy',
        method: 'delete',
        params: {id}
    })
}

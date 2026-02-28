# 微商城系统需求规格文档

## 1. 系统概述

| 项目 | 说明 |
|------|------|
| 系统名称 | 微商城系统 |
| 项目模式 | B2B2C 商家入驻（仅支持企业店） |
| 终端覆盖 | 微信小程序 + H5微商城 + iOS App + Android App |
| 技术栈 | 前端：Next.js (Web) + Uni-app (小程序/H5) + KMP (iOS/Android)；后端：Laravel + MySQL + Redis |
| 支付方式 | 微信支付、支付宝 |

---

## 2. 功能模块

### 2.1 用户端（买家）

| 模块 | 功能点 |
|------|--------|
| 首页 | banner轮播图、分类快捷入口、推荐商品、活动专区、搜索入口 |
| 商品列表 | 分类筛选、排序（销量/价格/新品）、筛选（价格区间） |
| 商品详情 | 商品图片相册、规格选择、库存显示、加入购物车、立即购买、收藏、分享 |
| 购物车 | 商品列表、数量修改、规格切换、删除、批量操作、全选结算 |
| 订单流程 | 确认订单、选择地址、选择配送方式、选择支付方式、提交订单 |
| 订单状态 | 待付款、待发货、待收货、已完成、已取消、售后中 |
| 物流跟踪 | 物流信息展示、快递公司、预计送达时间 |
| 收藏功能 | 收藏商品、收藏店铺、收藏列表管理 |
| 会员中心 | 个人资料、头像修改、收货地址管理、我的积分、我的订单入口 |
| 评价系统 | 评分、文字评价、图片评价、追评、好评率展示 |

### 2.2 商家端

| 模块 | 功能点 |
|------|--------|
| 店铺管理 | 店铺信息设置、店铺头像/店招、营业状态开关 |
| 店铺装修 | 首页布局配置（轮播、分类、公告、商品） |
| 商品管理 | 商品列表、新增商品、编辑商品、上架/下架、删除、库存管理 |
| 商品分类 | 店铺内商品分类管理 |
| 订单管理 | 订单列表、订单详情、发货、查看买家信息、取消订单 |
| 售后处理 | 退款申请处理、退货申请处理、售后记录 |
| 财务管理 | 账户余额、提现申请、提现记录、流水明细、对账单 |
| 数据统计 | 销售额、订单量、访客数、商品销量排行 |

### 2.3 平台端（管理员）

| 模块 | 功能点 |
|------|--------|
| 商家管理 | 商家入驻审核、商家列表、商家详情、禁用/启用商家 |
| 商品管理 | 商品列表、商品审核、类目管理、品牌管理 |
| 订单管理 | 订单列表、订单详情、异常订单处理 |
| 售后管理 | 退款/退货审核、售后记录查询 |
| 财务管理 | 结算管理、商家提现审核、平台收入统计 |
| 内容管理 | 文章管理、公告管理、广告位管理 |
| 营销管理 | 优惠券配置、活动专区管理 |
| 会员管理 | 会员列表、会员详情、会员积分管理 |
| 统计报表 | 销售报表、流量统计、会员分析、商品热销排行 |
| 系统管理 | 权限角色管理、操作日志、系统配置（支付/配送） |
| 配送管理 | 快递公司管理、配送模板设置 |

---

## 3. 支付与配送

### 3.1 支付方式

| 支付方式 | 说明 |
|----------|------|
| 微信支付 | 微信小程序内唤起微信支付 |
| 支付宝 | H5/App唤起支付宝支付 |

### 3.2 配送方式

| 配送方式 | 说明 |
|----------|------|
| 快递配送 | 商家发货，买家收货 |
| 到店自提 | 买家到店自取（需配置自提点） |

---

## 4. 数据库核心表设计

### 4.1 用户相关

| 表名 | 说明 |
|------|------|
| users | 用户表 |
| user_addresses | 用户收货地址 |
| user_tokens | 用户Token |
| user_favorites | 用户收藏 |

### 4.2 商家相关

| 表名 | 说明 |
|------|------|
| shops | 店铺表 |
| shop_categories | 店铺分类 |

### 4.3 商品相关

| 表名 | 说明 |
|------|------|
| categories | 商品分类 |
| brands | 品牌表 |
| products | 商品表 |
| product_images | 商品图片 |
| product_specs | 商品规格 |
| product_spec_values | 规格值 |

### 4.4 订单相关

| 表名 | 说明 |
|------|------|
| orders | 订单主表 |
| order_items | 订单商品项 |
| order_refunds | 退款/退货 |

### 4.5 支付相关

| 表名 | 说明 |
|------|------|
| payments | 支付记录 |

### 4.6 营销相关

| 表名 | 说明 |
|------|------|
| coupons | 优惠券 |
| coupon_users | 用户优惠券 |

### 4.7 内容相关

| 表名 | 说明 |
|------|------|
| articles | 文章 |
| advertise | 广告位 |

### 4.8 后台相关

| 表名 | 说明 |
|------|------|
| admin_users | 管理员 |
| admin_roles | 角色 |
| admin_permissions | 权限 |

---

## 5. API 接口规划

### 5.1 认证模块

- POST /api/auth/login - 登录
- POST /api/auth/register - 注册
- POST /api/auth/refresh - 刷新Token
- POST /api/auth/logout - 登出

### 5.2 用户模块

- GET /api/user - 获取用户信息
- PUT /api/user - 更新用户信息
- GET /api/user/addresses - 收货地址列表
- POST /api/user/addresses - 添加地址
- PUT /api/user/addresses/{id} - 更新地址
- DELETE /api/user/addresses/{id} - 删除地址
- GET /api/user/favorites - 收藏列表
- POST /api/user/favorites - 添加收藏
- DELETE /api/user/favorites/{id} - 取消收藏

### 5.3 商品模块

- GET /api/categories - 分类列表
- GET /api/brands - 品牌列表
- GET /api/products - 商品列表
- GET /api/products/{id} - 商品详情
- GET /api/products/search - 搜索商品

### 5.4 购物车模块

- GET /api/cart - 购物车列表
- POST /api/cart - 添加商品
- PUT /api/cart/{id} - 更新数量/规格
- DELETE /api/cart/{id} - 删除商品
- DELETE /api/cart - 清空购物车

### 5.5 订单模块

- POST /api/orders - 创建订单
- GET /api/orders - 订单列表
- GET /api/orders/{id} - 订单详情
- PUT /api/orders/{id}/cancel - 取消订单
- PUT /api/orders/{id}/confirm - 确认收货
- POST /api/orders/{id}/refund - 申请退款

### 5.6 支付模块

- POST /api/payments/{orderId}/wechat - 微信支付
- POST /api/payments/{orderId}/alipay - 支付宝
- POST /api/payments/notify - 支付回调

### 5.7 商家模块

- POST /api/shop/apply - 商家入驻申请
- GET /api/shop - 店铺信息
- PUT /api/shop - 店铺设置
- GET /api/shop/products - 店铺商品
- POST /api/shop/products - 添加商品
- PUT /api/shop/products/{id} - 编辑商品
- DELETE /api/shop/products/{id} - 删除商品
- GET /api/shop/orders - 店铺订单
- PUT /api/shop/orders/{id}/ship - 发货

### 5.8 平台模块

- GET /admin/shops - 商家列表
- PUT /admin/shops/{id}/verify - 审核商家
- GET /admin/products - 商品列表
- PUT /admin/products/{id}/audit - 审核商品
- GET /admin/orders - 订单列表
- GET /admin/refunds - 售后列表
- PUT /admin/refunds/{id}/handle - 处理售后

---

## 6. 营销功能

- 拼团功能
- 砍价功能
- 分销功能（裂变）
- 会员卡
- 积分商城

---


## 7. 版本记录

| 版本 | 日期 | 说明 |
|------|------|------|
| v1.0 | 2026-02-26 | 初始版本，涵盖核心交易功能 |


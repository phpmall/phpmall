# B2B2C 商城系统实施方案文档

> **文档版本**：v2.0  
> **编写日期**：2026年6月  
> **适用项目**：PHP B2B2C 多商户电商平台  
> **目标读者**：项目经理、技术负责人、开发团队、DevOps 工程师

---

## 目录

1. [Monorepo 开发工作流总结](#1-monorepo-开发工作流总结)
2. [多端控制器目录结构设计](#2-多端控制器目录结构设计)
3. [环境搭建指南](#3-环境搭建指南)
4. [部署流程](#4-部署流程)
5. [代码规范](#5-代码规范)

---


## 1. Monorepo 开发工作流总结

| 场景 | 命令 | 说明 |
|------|------|------|
| 首次安装 | `pnpm install` | 根目录执行，安装所有 workspace 依赖 |
| 启动所有应用 | `pnpm dev` | 并行启动 api / pc-mall / admin / h5-uniapp |
| 只启动后端 | `pnpm dev --filter=api` | 单独开发 Laravel 接口 |
| 只启动 PC 商城 | `pnpm dev --filter=pc-mall` | 单独开发前端 |
| 生成 API 类型 | `pnpm generate-api` | 从 Laravel 生成 TS 类型到 api-contract |
| 构建所有 | `pnpm build` | 按依赖顺序构建所有应用 |
| 构建受影响 | `pnpm turbo run build --affected` | 只构建变更的应用 |
| 代码检查 | `pnpm lint` | 检查所有应用的代码 |
| PHP 测试 | `pnpm --filter=api test` | 运行 Laravel 单元测试 |
| 数据库迁移 | `pnpm api:migrate` | 执行 Laravel 迁移 |
| 清理缓存 | `pnpm clean` | 清理所有构建产物和 node_modules |

> **文档结束**  
> 本文档应随项目迭代持续更新，建议在每次架构变更或技术升级后同步修订。


---


---

## 2. 多端控制器目录结构设计

基于图片中的 7 个目录（Admin、Common、Portal、Seller、Shop、Supplier、User），以下是完整的控制器目录、路由、中间件和权限体系设计。

### 2.1 目录结构定义

```
app/Http/Controllers/
├── Admin/          # 平台运营端（管理后台 API）
├── Common/         # 公共接口（无鉴权，多端共享）
├── Portal/         # 门户/首页（SEO 页面、CMS、运营位）
├── Seller/         # 商家端（商家后台 API）
├── Shop/           # 店铺/C端（消费者端 API）
├── Supplier/       # 供应商端（供应链端 API，可选）
├── User/           # 用户端（个人中心、用户相关）
└── Controller.php  # 基础控制器
```

### 2.2 各目录详细用途

| 目录 | 端 | 鉴权方式 | 路由前缀 | 用途 |
|------|------|---------|---------|------|
| `Admin` | 平台运营 | Sanctum + RBAC | `/api/admin/v1` | 平台管理后台：商家审核、商品审核、订单仲裁、财务管理、系统配置 |
| `Seller` | 商家 | Sanctum + 商家权限 | `/api/seller/v1` | 商家后台：商品管理、订单处理、库存、营销工具、数据报表、结算提现 |
| `Shop` | C端消费者 | 游客/Sanctum | `/api/shop/v1` | 商城前端：商品浏览、搜索、购物车、下单、支付、售后 |
| `User` | 注册用户 | Sanctum | `/api/user/v1` | 个人中心：地址、订单、收藏、足迹、消息、分销、钱包 |
| `Portal` | 全端 | 无鉴权/游客 | `/api/portal/v1` | 公共内容：首页、分类、广告位、CMS文章、帮助中心、搜索引擎 |
| `Common` | 全端 | 无鉴权 | `/api/common/v1` | 纯工具接口：上传、验证码、地区、物流查询、支付回调 |
| `Supplier` | 供应商 | Sanctum + 供应商权限 | `/api/supplier/v1` | 供应链：采购、发货、对账（可选，视业务规模） |

### 2.3 各目录控制器详细列表

```
// app/Http/Controllers/Admin/  —— 平台运营端
├── AuthController.php              # 管理员登录/登出/刷新Token
├── AdminController.php             # 管理员CRUD（超级管理员管理运营人员）
├── RoleController.php              # 角色管理（RBAC）
├── PermissionController.php          # 权限管理
├── DashboardController.php         # 数据仪表盘（GMV、订单、用户统计）
├── MerchantController.php          # 商家管理（入驻审核、资质查看、冻结）
├── MerchantAuditController.php   # 商家入驻审核流
├── ProductController.php           # 平台商品管理（审核、下架、违规处理）
├── ProductCategoryController.php # 平台类目管理（后台维护类目树）
├── OrderController.php             # 平台订单管理（仲裁、退款审核）
├── OrderRefundController.php       # 退款/售后仲裁
├── UserController.php              # 用户管理（封禁、查看行为）
├── FinanceController.php         # 财务管理（平台资金池、分账记录）
├── SettlementController.php      # 商家结算审核
├── WithdrawController.php        # 提现审核
├── CouponController.php          # 平台优惠券管理
├── ActivityController.php        # 平台营销活动（秒杀、拼团、满减）
├── SystemConfigController.php    # 系统参数配置
├── LogController.php             # 操作日志
├── BannerController.php          # 广告位管理
├── CMSController.php             # CMS文章/帮助中心
└── ReportController.php          # 数据报表导出

// app/Http/Controllers/Seller/  —— 商家端
├── AuthController.php              # 商家登录/注册/找回密码
├── ShopController.php            # 店铺信息设置（名称、logo、公告、运费模板）
├── ProductController.php         # 商品发布/编辑/上下架/库存管理
├── ProductCategoryController.php # 商家自定义分类（店铺内分类）
├── SKUController.php             # SKU管理（价格、库存、条码）
├── OrderController.php           # 订单列表/发货/修改地址/取消
├── OrderRefundController.php     # 售后处理（同意退款/拒绝/换货）
├── ExpressController.php       # 物流管理/快递单打印/电子面单
├── MarketingController.php       # 商家营销（优惠券、满减、拼团）
├── DataController.php          # 经营数据（访客、成交、转化率）
├── FinanceController.php       # 资金概览/对账单/结算明细
├── WithdrawController.php      # 提现申请
├── SubAccountController.php    # 子账号管理（客服、运营、财务）
├── MessageController.php       # 站内消息/客服会话
├── LiveController.php          # 直播管理（如有）
└── SettingsController.php      # 店铺设置/资质信息

// app/Http/Controllers/Shop/  —— C端消费者端
├── HomeController.php            # 首页数据（轮播、推荐、分类入口、秒杀）
├── CategoryController.php        # 分类页/分类树/分类商品
├── ProductController.php       # 商品详情/商品列表/搜索
├── SearchController.php        # 全局搜索/筛选/排序/聚合
├── CartController.php          # 购物车（增删改查/选中/批量）
├── OrderController.php         # 下单/订单确认/订单列表/订单详情
├── PaymentController.php       # 支付发起/支付结果查询
├── AddressController.php       # 收货地址（增删改查/默认设置）
├── CouponController.php        # 用户优惠券列表/领券中心
├── ReviewController.php        # 商品评价/晒单/追评
├── FavoriteController.php      # 收藏商品/收藏店铺
├── FootprintController.php     # 浏览足迹
├── SeckillController.php       # 秒杀专场/秒杀商品
├── GroupBuyController.php      # 拼团/社区团购
└── LiveController.php        # 直播间/直播商品列表

// app/Http/Controllers/User/  —— 用户个人中心
├── AuthController.php          # 注册/登录/短信验证码/微信授权
├── ProfileController.php       # 个人信息/头像/昵称
├── AddressController.php       # 收货地址（与Shop端共享表，但独立路由）
├── OrderController.php       # 我的订单/订单详情/确认收货/取消
├── OrderRefundController.php # 申请退款/退款进度/退货物流
├── WalletController.php      # 余额/充值/提现记录
├── PointsController.php      # 积分/积分明细
├── DistributionController.php # 分销中心/邀请海报/佣金明细
├── MessageController.php     # 消息通知/站内信
├── AfterSaleController.php   # 售后记录/客服对话
├── SecurityController.php  # 修改密码/手机绑定/实名认证
└── SettingsController.php  # 账号设置/隐私/注销

// app/Http/Controllers/Portal/  —— 公共门户/内容
├── HomeController.php        # 首页聚合数据
├── CategoryController.php    # 全部分类树（缓存）
├── BannerController.php    # 广告位/轮播图
├── CMSController.php       # 文章列表/文章详情/帮助中心
├── NoticeController.php    # 公告/弹窗通知
├── SearchHotController.php # 热搜词/搜索建议
└── SuggestController.php # 搜索联想/自动补全

// app/Http/Controllers/Common/  —— 公共工具（无鉴权）
├── UploadController.php    # 文件上传（图片/OSS直传）
├── CaptchaController.php # 图形验证码/短信验证码发送
├── RegionController.php  # 省市区三级联动/地址解析
├── ExpressController.php # 物流轨迹查询（快递100/菜鸟）
├── PaymentController.php # 支付异步回调（微信/支付宝/银联）
├── QrcodeController.php  # 二维码生成
├── ShortLinkController.php # 短链接生成/跳转
└── VersionController.php # App版本检查/强制更新

// app/Http/Controllers/Supplier/  —— 供应商端（可选）
├── AuthController.php
├── ProductController.php     # 供货商品管理
├── PurchaseOrderController.php # 采购订单
├── WarehouseController.php   # 仓库/库存
├── DeliveryController.php    # 供货发货
└── ReconciliationController.php # 对账
```

### 2.4 路由配置（`routes/` 目录）

```
routes/
├── api/
│   ├── admin.php      # 平台运营端路由
│   ├── seller.php     # 商家端路由
│   ├── shop.php       # C端消费者路由
│   ├── user.php       # 用户个人中心路由
│   ├── portal.php     # 公共门户路由
│   ├── common.php     # 公共工具路由（无鉴权）
│   └── supplier.php   # 供应商端路由（可选）
└── web.php            # 传统web路由（如需要）
```

#### `routes/api/admin.php` —— 平台运营端

```php
<?php

use App\Http\Controllers\Admin\{
    AuthController,
    DashboardController,
    MerchantController,
    MerchantAuditController,
    ProductController,
    OrderController,
    OrderRefundController,
    UserController,
    FinanceController,
    SettlementController,
    WithdrawController,
    CouponController,
    ActivityController,
    SystemConfigController,
    LogController,
    BannerController,
    CMSController,
    ReportController,
    AdminController,
    RoleController,
    PermissionController,
};

// 管理员认证（无鉴权）
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('admin.auth.login');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('admin.auth.refresh');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('admin.auth.logout');
});

// 需要管理员鉴权
Route::middleware(['auth:sanctum', 'admin.auth', 'admin.permission'])->group(function () {
    
    // 仪表盘
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('dashboard/realtime', [DashboardController::class, 'realtime'])->name('admin.dashboard.realtime');
    
    // 管理员与权限
    Route::apiResource('admins', AdminController::class)->names('admin.admins');
    Route::apiResource('roles', RoleController::class)->names('admin.roles');
    Route::apiResource('permissions', PermissionController::class)->names('admin.permissions');
    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('admin.roles.permissions');
    Route::put('roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('admin.roles.permissions.sync');
    
    // 商家管理
    Route::apiResource('merchants', MerchantController::class)->names('admin.merchants');
    Route::put('merchants/{merchant}/freeze', [MerchantController::class, 'freeze'])->name('admin.merchants.freeze');
    Route::put('merchants/{merchant}/unfreeze', [MerchantController::class, 'unfreeze'])->name('admin.merchants.unfreeze');
    Route::get('merchants/{merchant}/statistics', [MerchantController::class, 'statistics'])->name('admin.merchants.statistics');
    
    // 商家入驻审核
    Route::get('merchant-audits', [MerchantAuditController::class, 'index'])->name('admin.merchant-audits.index');
    Route::get('merchant-audits/{audit}', [MerchantAuditController::class, 'show'])->name('admin.merchant-audits.show');
    Route::put('merchant-audits/{audit}/approve', [MerchantAuditController::class, 'approve'])->name('admin.merchant-audits.approve');
    Route::put('merchant-audits/{audit}/reject', [MerchantAuditController::class, 'reject'])->name('admin.merchant-audits.reject');
    
    // 商品管理（平台视角）
    Route::get('products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('admin.products.show');
    Route::put('products/{product}/audit', [ProductController::class, 'audit'])->name('admin.products.audit');
    Route::put('products/{product}/force-offline', [ProductController::class, 'forceOffline'])->name('admin.products.force-offline');
    
    // 订单管理（平台仲裁）
    Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('orders/{order}/arbitrate', [OrderController::class, 'arbitrate'])->name('admin.orders.arbitrate');
    
    // 退款/售后仲裁
    Route::get('refunds', [OrderRefundController::class, 'index'])->name('admin.refunds.index');
    Route::get('refunds/{refund}', [OrderRefundController::class, 'show'])->name('admin.refunds.show');
    Route::put('refunds/{refund}/approve', [OrderRefundController::class, 'approve'])->name('admin.refunds.approve');
    Route::put('refunds/{refund}/reject', [OrderRefundController::class, 'reject'])->name('admin.refunds.reject');
    
    // 用户管理
    Route::apiResource('users', UserController::class)->names('admin.users');
    Route::put('users/{user}/ban', [UserController::class, 'ban'])->name('admin.users.ban');
    Route::put('users/{user}/unban', [UserController::class, 'unban'])->name('admin.users.unban');
    
    // 财务管理
    Route::get('finance/overview', [FinanceController::class, 'overview'])->name('admin.finance.overview');
    Route::get('finance/flow', [FinanceController::class, 'flow'])->name('admin.finance.flow');
    Route::get('finance/profit-sharing', [FinanceController::class, 'profitSharing'])->name('admin.finance.profit-sharing');
    
    // 结算与提现
    Route::get('settlements', [SettlementController::class, 'index'])->name('admin.settlements.index');
    Route::put('settlements/{settlement}/confirm', [SettlementController::class, 'confirm'])->name('admin.settlements.confirm');
    Route::get('withdraws', [WithdrawController::class, 'index'])->name('admin.withdraws.index');
    Route::put('withdraws/{withdraw}/audit', [WithdrawController::class, 'audit'])->name('admin.withdraws.audit');
    
    // 营销管理
    Route::apiResource('coupons', CouponController::class)->names('admin.coupons');
    Route::apiResource('activities', ActivityController::class)->names('admin.activities');
    Route::put('activities/{activity}/enable', [ActivityController::class, 'enable'])->name('admin.activities.enable');
    Route::put('activities/{activity}/disable', [ActivityController::class, 'disable'])->name('admin.activities.disable');
    
    // 系统配置
    Route::get('system-configs', [SystemConfigController::class, 'index'])->name('admin.system-configs.index');
    Route::put('system-configs', [SystemConfigController::class, 'batchUpdate'])->name('admin.system-configs.batch-update');
    
    // 日志
    Route::get('logs', [LogController::class, 'index'])->name('admin.logs.index');
    Route::get('logs/{log}', [LogController::class, 'show'])->name('admin.logs.show');
    
    // 内容管理
    Route::apiResource('banners', BannerController::class)->names('admin.banners');
    Route::apiResource('cms', CMSController::class)->names('admin.cms');
    Route::put('cms/{cms}/publish', [CMSController::class, 'publish'])->name('admin.cms.publish');
    
    // 数据报表
    Route::get('reports/orders', [ReportController::class, 'orders'])->name('admin.reports.orders');
    Route::get('reports/merchants', [ReportController::class, 'merchants'])->name('admin.reports.merchants');
    Route::get('reports/users', [ReportController::class, 'users'])->name('admin.reports.users');
    Route::get('reports/export', [ReportController::class, 'export'])->name('admin.reports.export');
});
```

#### `routes/api/seller.php` —— 商家端

```php
<?php

use App\Http\Controllers\Seller\{
    AuthController,
    ShopController,
    ProductController,
    ProductCategoryController,
    SKUController,
    OrderController,
    OrderRefundController,
    ExpressController,
    MarketingController,
    DataController,
    FinanceController,
    WithdrawController,
    SubAccountController,
    MessageController,
    LiveController,
    SettingsController,
};

// 商家认证（无鉴权）
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('seller.auth.login');
    Route::post('register', [AuthController::class, 'register'])->name('seller.auth.register');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('seller.auth.refresh');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('seller.auth.logout');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('seller.auth.forgot-password');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('seller.auth.reset-password');
});

// 需要商家鉴权
Route::middleware(['auth:sanctum', 'seller.auth'])->group(function () {
    
    // 店铺信息
    Route::get('shop', [ShopController::class, 'show'])->name('seller.shop.show');
    Route::put('shop', [ShopController::class, 'update'])->name('seller.shop.update');
    Route::put('shop/announcement', [ShopController::class, 'announcement'])->name('seller.shop.announcement');
    Route::apiResource('shop/categories', ProductCategoryController::class)->names('seller.shop.categories');
    
    // 商品管理
    Route::apiResource('products', ProductController::class)->names('seller.products');
    Route::put('products/{product}/on-sale', [ProductController::class, 'onSale'])->name('seller.products.on-sale');
    Route::put('products/{product}/off-sale', [ProductController::class, 'offSale'])->name('seller.products.off-sale');
    Route::put('products/batch/on-sale', [ProductController::class, 'batchOnSale'])->name('seller.products.batch-on-sale');
    Route::put('products/batch/off-sale', [ProductController::class, 'batchOffSale'])->name('seller.products.batch-off-sale');
    Route::put('products/batch/delete', [ProductController::class, 'batchDelete'])->name('seller.products.batch-delete');
    
    // SKU管理
    Route::get('products/{product}/skus', [SKUController::class, 'index'])->name('seller.skus.index');
    Route::put('products/{product}/skus/batch', [SKUController::class, 'batchUpdate'])->name('seller.skus.batch-update');
    Route::put('skus/{sku}/stock', [SKUController::class, 'updateStock'])->name('seller.skus.update-stock');
    Route::put('skus/{sku}/price', [SKUController::class, 'updatePrice'])->name('seller.skus.update-price');
    
    // 订单管理
    Route::get('orders', [OrderController::class, 'index'])->name('seller.orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('seller.orders.show');
    Route::put('orders/{order}/ship', [OrderController::class, 'ship'])->name('seller.orders.ship');
    Route::put('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('seller.orders.cancel');
    Route::put('orders/{order}/modify-address', [OrderController::class, 'modifyAddress'])->name('seller.orders.modify-address');
    Route::put('orders/batch/ship', [OrderController::class, 'batchShip'])->name('seller.orders.batch-ship');
    
    // 售后处理
    Route::get('refunds', [OrderRefundController::class, 'index'])->name('seller.refunds.index');
    Route::get('refunds/{refund}', [OrderRefundController::class, 'show'])->name('seller.refunds.show');
    Route::put('refunds/{refund}/agree', [OrderRefundController::class, 'agree'])->name('seller.refunds.agree');
    Route::put('refunds/{refund}/reject', [OrderRefundController::class, 'reject'])->name('seller.refunds.reject');
    Route::put('refunds/{refund}/confirm-receipt', [OrderRefundController::class, 'confirmReceipt'])->name('seller.refunds.confirm-receipt');
    
    // 物流
    Route::get('express/companies', [ExpressController::class, 'companies'])->name('seller.express.companies');
    Route::post('express/print', [ExpressController::class, 'print'])->name('seller.express.print');
    Route::get('express/{expressNo}/track', [ExpressController::class, 'track'])->name('seller.express.track');
    
    // 营销工具
    Route::apiResource('coupons', MarketingController::class)->names('seller.coupons');
    Route::get('marketing/full-reductions', [MarketingController::class, 'fullReductions'])->name('seller.marketing.full-reductions');
    Route::get('marketing/seckills', [MarketingController::class, 'seckills'])->name('seller.marketing.seckills');
    Route::post('marketing/seckills', [MarketingController::class, 'createSeckill'])->name('seller.marketing.seckills.create');
    
    // 经营数据
    Route::get('data/overview', [DataController::class, 'overview'])->name('seller.data.overview');
    Route::get('data/visitors', [DataController::class, 'visitors'])->name('seller.data.visitors');
    Route::get('data/orders', [DataController::class, 'orders'])->name('seller.data.orders');
    Route::get('data/products', [DataController::class, 'products'])->name('seller.data.products');
    Route::get('data/export', [DataController::class, 'export'])->name('seller.data.export');
    
    // 财务
    Route::get('finance/overview', [FinanceController::class, 'overview'])->name('seller.finance.overview');
    Route::get('finance/bills', [FinanceController::class, 'bills'])->name('seller.finance.bills');
    Route::get('finance/settlements', [FinanceController::class, 'settlements'])->name('seller.finance.settlements');
    Route::get('finance/settlements/{settlement}', [FinanceController::class, 'settlementDetail'])->name('seller.finance.settlements.detail');
    
    // 提现
    Route::get('withdraws', [WithdrawController::class, 'index'])->name('seller.withdraws.index');
    Route::post('withdraws', [WithdrawController::class, 'apply'])->name('seller.withdraws.apply');
    Route::get('withdraws/balance', [WithdrawController::class, 'balance'])->name('seller.withdraws.balance');
    
    // 子账号
    Route::apiResource('sub-accounts', SubAccountController::class)->names('seller.sub-accounts');
    Route::put('sub-accounts/{subAccount}/enable', [SubAccountController::class, 'enable'])->name('seller.sub-accounts.enable');
    Route::put('sub-accounts/{subAccount}/disable', [SubAccountController::class, 'disable'])->name('seller.sub-accounts.disable');
    
    // 消息
    Route::get('messages', [MessageController::class, 'index'])->name('seller.messages.index');
    Route::put('messages/{message}/read', [MessageController::class, 'read'])->name('seller.messages.read');
    Route::put('messages/batch-read', [MessageController::class, 'batchRead'])->name('seller.messages.batch-read');
    
    // 设置
    Route::get('settings', [SettingsController::class, 'show'])->name('seller.settings.show');
    Route::put('settings', [SettingsController::class, 'update'])->name('seller.settings.update');
    Route::put('settings/password', [SettingsController::class, 'password'])->name('seller.settings.password');
});
```

#### `routes/api/shop.php` —— C端消费者

```php
<?php

use App\Http\Controllers\Shop\{
    HomeController,
    CategoryController,
    ProductController,
    SearchController,
    CartController,
    OrderController,
    PaymentController,
    AddressController,
    CouponController,
    ReviewController,
    FavoriteController,
    FootprintController,
    SeckillController,
    GroupBuyController,
    LiveController,
};

// 无鉴权路由
Route::get('home', [HomeController::class, 'index'])->name('shop.home');
Route::get('home/recommend', [HomeController::class, 'recommend'])->name('shop.home.recommend');
Route::get('categories', [CategoryController::class, 'tree'])->name('shop.categories.tree');
Route::get('categories/{category}/products', [CategoryController::class, 'products'])->name('shop.categories.products');

// 商品（无鉴权）
Route::get('products', [ProductController::class, 'index'])->name('shop.products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('shop.products.show');
Route::get('products/{product}/reviews', [ProductController::class, 'reviews'])->name('shop.products.reviews');
Route::get('products/{product}/recommend', [ProductController::class, 'recommend'])->name('shop.products.recommend');

// 搜索（无鉴权）
Route::get('search', [SearchController::class, 'index'])->name('shop.search');
Route::get('search/suggest', [SearchController::class, 'suggest'])->name('shop.search.suggest');
Route::get('search/hot', [SearchController::class, 'hot'])->name('shop.search.hot');

// 秒杀/拼团（无鉴权）
Route::get('seckills', [SeckillController::class, 'index'])->name('shop.seckills.index');
Route::get('seckills/{seckill}', [SeckillController::class, 'show'])->name('shop.seckills.show');
Route::get('group-buys', [GroupBuyController::class, 'index'])->name('shop.group-buys.index');

// 需要用户鉴权
Route::middleware('auth:sanctum')->group(function () {
    
    // 购物车
    Route::get('cart', [CartController::class, 'index'])->name('shop.cart.index');
    Route::post('cart', [CartController::class, 'add'])->name('shop.cart.add');
    Route::put('cart/{cartItem}', [CartController::class, 'update'])->name('shop.cart.update');
    Route::delete('cart/{cartItem}', [CartController::class, 'remove'])->name('shop.cart.remove');
    Route::put('cart/batch/select', [CartController::class, 'batchSelect'])->name('shop.cart.batch-select');
    Route::delete('cart/batch/remove', [CartController::class, 'batchRemove'])->name('shop.cart.batch-remove');
    Route::get('cart/count', [CartController::class, 'count'])->name('shop.cart.count');
    
    // 订单
    Route::post('orders/preview', [OrderController::class, 'preview'])->name('shop.orders.preview');
    Route::post('orders', [OrderController::class, 'store'])->name('shop.orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('shop.orders.show');
    Route::put('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('shop.orders.cancel');
    Route::put('orders/{order}/confirm', [OrderController::class, 'confirm'])->name('shop.orders.confirm');
    Route::put('orders/{order}/delete', [OrderController::class, 'delete'])->name('shop.orders.delete');
    Route::post('orders/{order}/review', [OrderController::class, 'review'])->name('shop.orders.review');
    
    // 支付
    Route::post('orders/{order}/pay', [PaymentController::class, 'pay'])->name('shop.payment.pay');
    Route::get('payments/{payment}/query', [PaymentController::class, 'query'])->name('shop.payment.query');
    
    // 收货地址
    Route::apiResource('addresses', AddressController::class)->names('shop.addresses');
    Route::put('addresses/{address}/default', [AddressController::class, 'setDefault'])->name('shop.addresses.default');
    
    // 优惠券
    Route::get('coupons', [CouponController::class, 'index'])->name('shop.coupons.index');
    Route::get('coupons/available', [CouponController::class, 'available'])->name('shop.coupons.available');
    Route::post('coupons/{coupon}/claim', [CouponController::class, 'claim'])->name('shop.coupons.claim');
    Route::post('orders/preview/coupon', [CouponController::class, 'preview'])->name('shop.coupons.preview');
    
    // 收藏
    Route::get('favorites', [FavoriteController::class, 'index'])->name('shop.favorites.index');
    Route::post('favorites/products/{product}', [FavoriteController::class, 'addProduct'])->name('shop.favorites.add-product');
    Route::post('favorites/merchants/{merchant}', [FavoriteController::class, 'addMerchant'])->name('shop.favorites.add-merchant');
    Route::delete('favorites/products/{product}', [FavoriteController::class, 'removeProduct'])->name('shop.favorites.remove-product');
    Route::delete('favorites/merchants/{merchant}', [FavoriteController::class, 'removeMerchant'])->name('shop.favorites.remove-merchant');
    
    // 足迹
    Route::get('footprints', [FootprintController::class, 'index'])->name('shop.footprints.index');
    Route::post('footprints', [FootprintController::class, 'add'])->name('shop.footprints.add');
    Route::delete('footprints/batch', [FootprintController::class, 'batchDelete'])->name('shop.footprints.batch-delete');
    
    // 评价
    Route::get('reviews/me', [ReviewController::class, 'myReviews'])->name('shop.reviews.me');
    Route::post('orders/{order}/items/{item}/review', [ReviewController::class, 'create'])->name('shop.reviews.create');
    Route::post('reviews/{review}/append', [ReviewController::class, 'append'])->name('shop.reviews.append');
});
```

#### `routes/api/user.php` —— 用户个人中心

```php
<?php

use App\Http\Controllers\User\{
    AuthController,
    ProfileController,
    AddressController,
    OrderController,
    OrderRefundController,
    WalletController,
    PointsController,
    DistributionController,
    MessageController,
    AfterSaleController,
    SecurityController,
    SettingsController,
};

// 用户认证（无鉴权）
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('user.auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('user.auth.login');
    Route::post('login-sms', [AuthController::class, 'loginBySms'])->name('user.auth.login-sms');
    Route::post('login-wechat', [AuthController::class, 'loginByWechat'])->name('user.auth.login-wechat');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('user.auth.refresh');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('user.auth.logout');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('user.auth.forgot-password');
});

// 需要用户鉴权
Route::middleware('auth:sanctum')->group(function () {
    
    // 个人信息
    Route::get('profile', [ProfileController::class, 'show'])->name('user.profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::put('profile/avatar', [ProfileController::class, 'avatar'])->name('user.profile.avatar');
    Route::put('profile/nickname', [ProfileController::class, 'nickname'])->name('user.profile.nickname');
    
    // 收货地址（与 Shop 端共享表，但独立路由）
    Route::apiResource('addresses', AddressController::class)->names('user.addresses');
    Route::put('addresses/{address}/default', [AddressController::class, 'setDefault'])->name('user.addresses.default');
    
    // 我的订单
    Route::get('orders', [OrderController::class, 'index'])->name('user.orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('user.orders.show');
    Route::put('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('user.orders.cancel');
    Route::put('orders/{order}/confirm', [OrderController::class, 'confirm'])->name('user.orders.confirm');
    Route::delete('orders/{order}', [OrderController::class, 'delete'])->name('user.orders.delete');
    Route::get('orders/{order}/logistics', [OrderController::class, 'logistics'])->name('user.orders.logistics');
    
    // 退款/售后
    Route::get('refunds', [OrderRefundController::class, 'index'])->name('user.refunds.index');
    Route::get('refunds/{refund}', [OrderRefundController::class, 'show'])->name('user.refunds.show');
    Route::post('orders/{order}/refunds', [OrderRefundController::class, 'apply'])->name('user.refunds.apply');
    Route::put('refunds/{refund}/cancel', [OrderRefundController::class, 'cancel'])->name('user.refunds.cancel');
    Route::post('refunds/{refund}/logistics', [OrderRefundController::class, 'fillLogistics'])->name('user.refunds.fill-logistics');
    
    // 钱包
    Route::get('wallet', [WalletController::class, 'show'])->name('user.wallet.show');
    Route::get('wallet/logs', [WalletController::class, 'logs'])->name('user.wallet.logs');
    Route::post('wallet/recharge', [WalletController::class, 'recharge'])->name('user.wallet.recharge');
    Route::post('wallet/withdraw', [WalletController::class, 'withdraw'])->name('user.wallet.withdraw');
    
    // 积分
    Route::get('points', [PointsController::class, 'show'])->name('user.points.show');
    Route::get('points/logs', [PointsController::class, 'logs'])->name('user.points.logs');
    
    // 分销
    Route::get('distribution', [DistributionController::class, 'overview'])->name('user.distribution.overview');
    Route::get('distribution/invites', [DistributionController::class, 'invites'])->name('user.distribution.invites');
    Route::get('distribution/commissions', [DistributionController::class, 'commissions'])->name('user.distribution.commissions');
    Route::get('distribution/withdraws', [DistributionController::class, 'withdraws'])->name('user.distribution.withdraws');
    Route::post('distribution/withdraw', [DistributionController::class, 'withdraw'])->name('user.distribution.withdraw');
    Route::get('distribution/qrcode', [DistributionController::class, 'qrcode'])->name('user.distribution.qrcode');
    
    // 消息
    Route::get('messages', [MessageController::class, 'index'])->name('user.messages.index');
    Route::get('messages/unread-count', [MessageController::class, 'unreadCount'])->name('user.messages.unread-count');
    Route::put('messages/{message}/read', [MessageController::class, 'read'])->name('user.messages.read');
    Route::put('messages/batch-read', [MessageController::class, 'batchRead'])->name('user.messages.batch-read');
    Route::delete('messages/{message}', [MessageController::class, 'delete'])->name('user.messages.delete');
    
    // 售后/客服
    Route::get('after-sales', [AfterSaleController::class, 'index'])->name('user.after-sales.index');
    Route::get('after-sales/{afterSale}', [AfterSaleController::class, 'show'])->name('user.after-sales.show');
    Route::post('after-sales/{afterSale}/message', [AfterSaleController::class, 'sendMessage'])->name('user.after-sales.message');
    
    // 安全
    Route::put('security/password', [SecurityController::class, 'password'])->name('user.security.password');
    Route::put('security/phone', [SecurityController::class, 'phone'])->name('user.security.phone');
    Route::put('security/email', [SecurityController::class, 'email'])->name('user.security.email');
    Route::post('security/real-name', [SecurityController::class, 'realName'])->name('user.security.real-name');
    
    // 设置
    Route::get('settings', [SettingsController::class, 'show'])->name('user.settings.show');
    Route::put('settings', [SettingsController::class, 'update'])->name('user.settings.update');
    Route::post('settings/deactivate', [SettingsController::class, 'deactivate'])->name('user.settings.deactivate');
});
```

#### `routes/api/portal.php` —— 公共门户

```php
<?php

use App\Http\Controllers\Portal\{
    HomeController,
    CategoryController,
    BannerController,
    CMSController,
    NoticeController,
    SearchHotController,
    SuggestController,
};

// 全部无鉴权，可缓存
Route::get('home', [HomeController::class, 'index'])->name('portal.home');
Route::get('home/banners', [BannerController::class, 'index'])->name('portal.banners');
Route::get('categories', [CategoryController::class, 'tree'])->name('portal.categories');
Route::get('categories/{category}/breadcrumb', [CategoryController::class, 'breadcrumb'])->name('portal.categories.breadcrumb');

Route::get('cms', [CMSController::class, 'index'])->name('portal.cms.index');
Route::get('cms/{cms}', [CMSController::class, 'show'])->name('portal.cms.show');
Route::get('cms/{cms}/related', [CMSController::class, 'related'])->name('portal.cms.related');

Route::get('notices', [NoticeController::class, 'index'])->name('portal.notices.index');
Route::get('notices/{notice}', [NoticeController::class, 'show'])->name('portal.notices.show');

Route::get('search/hot', [SearchHotController::class, 'index'])->name('portal.search.hot');
Route::get('search/suggest', [SuggestController::class, 'suggest'])->name('portal.search.suggest');
```

#### `routes/api/common.php` —— 公共工具（无鉴权）

```php
<?php

use App\Http\Controllers\Common\{
    UploadController,
    CaptchaController,
    RegionController,
    ExpressController,
    PaymentController,
    QrcodeController,
    ShortLinkController,
    VersionController,
};

Route::post('upload/image', [UploadController::class, 'image'])->name('common.upload.image');
Route::post('upload/file', [UploadController::class, 'file'])->name('common.upload.file');
Route::post('upload/oss-policy', [UploadController::class, 'ossPolicy'])->name('common.upload.oss-policy');

Route::get('captcha/image', [CaptchaController::class, 'image'])->name('common.captcha.image');
Route::post('captcha/sms', [CaptchaController::class, 'sms'])->name('common.captcha.sms');
Route::post('captcha/verify', [CaptchaController::class, 'verify'])->name('common.captcha.verify');

Route::get('regions', [RegionController::class, 'index'])->name('common.regions');
Route::get('regions/{code}/children', [RegionController::class, 'children'])->name('common.regions.children');

Route::get('express/{expressNo}/track', [ExpressController::class, 'track'])->name('common.express.track');

Route::post('payments/wechat/notify', [PaymentController::class, 'wechatNotify'])->name('common.payments.wechat.notify');
Route::post('payments/alipay/notify', [PaymentController::class, 'alipayNotify'])->name('common.payments.alipay.notify');
Route::post('payments/unionpay/notify', [PaymentController::class, 'unionpayNotify'])->name('common.payments.unionpay.notify');

Route::get('qrcode', [QrcodeController::class, 'generate'])->name('common.qrcode.generate');

Route::post('short-links', [ShortLinkController::class, 'create'])->name('common.short-links.create');
Route::get('s/{code}', [ShortLinkController::class, 'redirect'])->name('common.short-links.redirect');

Route::get('version/check', [VersionController::class, 'check'])->name('common.version.check');
```

### 2.5 路由与中间件注册（Laravel 13：`bootstrap/app.php`）

Laravel 13 将路由注册和中间件配置统一迁移到 `bootstrap/app.php`，不再使用 `RouteServiceProvider` 和 `app/Http/Kernel.php`。

```php
// bootstrap/app.php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // 传统 web 路由（如需 SEO 页面）
        web: __DIR__ . '/../routes/web.php',
        // 命令行路由
        commands: __DIR__ . '/../routes/console.php',
        // 健康检查路由
        health: '/up',
        // 多端 API 路由
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(function () {
                    // 平台运营端
                    Route::prefix('admin/v1')
                        ->middleware(['api', 'throttle:admin'])
                        ->group(base_path('routes/api/admin.php'));
                    
                    // 商家端
                    Route::prefix('seller/v1')
                        ->middleware(['api', 'throttle:seller'])
                        ->group(base_path('routes/api/seller.php'));
                    
                    // C端消费者
                    Route::prefix('shop/v1')
                        ->middleware(['api', 'throttle:shop'])
                        ->group(base_path('routes/api/shop.php'));
                    
                    // 用户个人中心
                    Route::prefix('user/v1')
                        ->middleware(['api', 'throttle:user'])
                        ->group(base_path('routes/api/user.php'));
                    
                    // 公共门户
                    Route::prefix('portal/v1')
                        ->middleware(['api', 'throttle:portal'])
                        ->group(base_path('routes/api/portal.php'));
                    
                    // 公共工具
                    Route::prefix('common/v1')
                        ->middleware(['api', 'throttle:common'])
                        ->group(base_path('routes/api/common.php'));
                    
                    // 供应商端
                    Route::prefix('supplier/v1')
                        ->middleware(['api', 'throttle:seller'])
                        ->group(base_path('routes/api/supplier.php'));
                });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ========== 全局中间件 ==========
        $middleware->use([
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // ========== 中间件分组（替代 Kernel $middlewareGroups）==========
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // ========== 中间件别名（替代 Kernel $middlewareAliases）==========
        $middleware->alias([
            // 原生认证
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            
            // 多端鉴权中间件
            'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class,
            'admin.permission' => \App\Http\Middleware\AdminPermission::class,
            'seller.auth' => \App\Http\Middleware\SellerAuthenticate::class,
            'seller.permission' => \App\Http\Middleware\SellerPermission::class,
            'supplier.auth' => \App\Http\Middleware\SupplierAuthenticate::class,
        ]);

        // ========== 优先级（替代 Kernel $middlewarePriority）==========
        $middleware->priority([
            \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Routing\Middleware\ThrottleRequestsWithRedis::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Auth\Middleware\Authenticate::class,
            \Illuminate\Auth\Middleware\Authorize::class,
        ]);

        // ========== 路由中间件验证（ Laravel 13 新特性）==========
        // 确保路由中使用的中间件都在别名中注册
        $middleware->validateCsrfTokens(exclude: [
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 自定义异常处理
        $exceptions->renderable(function (\App\Exceptions\ApiException $e) {
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        });
    })
    ->withSchedule(function ($schedule) {
        // 定时任务注册
        $schedule->command('reconciliation:run')->dailyAt('02:00');
        $schedule->command('settlements:generate')->dailyAt('03:00');
        $schedule->command('orders:timeout-cancel')->everyFiveMinutes();
    })
    ->create();
```

**Laravel 13 变更要点对比**

| Laravel 12 | Laravel 13 | 说明 |
|------------|-----------|------|
| `app/Providers/RouteServiceProvider.php` | `bootstrap/app.php` → `withRouting()` | 路由注册统一入口 |
| `app/Http/Kernel.php` | `bootstrap/app.php` → `withMiddleware()` | 中间件配置统一入口 |
| `Kernel::$middleware` | `$middleware->use()` | 全局中间件 |
| `Kernel::$middlewareGroups` | `$middleware->group()` | 中间件分组 |
| `Kernel::$middlewareAliases` | `$middleware->alias()` | 中间件别名 |
| `Kernel::$middlewarePriority` | `$middleware->priority()` | 中间件优先级 |
| `RouteServiceProvider::boot()` | `withRouting()` 回调 | 路由定义 |
| `RateLimiter::for()` 在 ServiceProvider | `RateLimiter::for()` 在 `withRouting()` 或 `withMiddleware()` | 限流定义可前置 |

---

**限流配置（`routes/api.php` 或 `bootstrap/app.php` 中）**

```php
// routes/api.php 或 bootstrap/app.php 顶部
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('admin', fn (Request $request) =>
    Limit::perMinute(300)->by($request->user()?->id ?: $request->ip())
);

RateLimiter::for('seller', fn (Request $request) =>
    Limit::perMinute(200)->by($request->user()?->id ?: $request->ip())
);

RateLimiter::for('shop', fn (Request $request) =>
    Limit::perMinute(100)->by($request->ip())
);

RateLimiter::for('user', fn (Request $request) =>
    Limit::perMinute(100)->by($request->user()?->id ?: $request->ip())
);

RateLimiter::for('portal', fn (Request $request) =>
    Limit::perMinute(500)->by($request->ip())
);

RateLimiter::for('common', fn (Request $request) =>
    Limit::perMinute(60)->by($request->ip())
);
```

### 2.6 自定义中间件

```php
// app/Http/Middleware/AdminAuthenticate.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => '无权访问'], 403);
        }
        
        return $next($request);
    }
}
```

```php
// app/Http/Middleware/SellerAuthenticate.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user || !$user->isSeller()) {
            return response()->json(['message' => '无权访问'], 403);
        }
        
        $merchant = $user->merchant;
        if (!$merchant || $merchant->status !== 'approved') {
            return response()->json(['message' => '商家未审核通过'], 403);
        }
        
        $request->attributes->set('current_merchant', $merchant);
        
        return $next($request);
    }
}
```

### 2.7 zircote/swagger-php Tag 组织

```php
// Admin 端
#[OA\Tag(name: 'Admin - Auth', description: '平台管理员认证')]
#[OA\Tag(name: 'Admin - Dashboard', description: '数据仪表盘')]
#[OA\Tag(name: 'Admin - Merchants', description: '商家管理')]
#[OA\Tag(name: 'Admin - Products', description: '平台商品管理')]
#[OA\Tag(name: 'Admin - Orders', description: '平台订单仲裁')]
#[OA\Tag(name: 'Admin - Finance', description: '财务管理')]
#[OA\Tag(name: 'Admin - Users', description: '用户管理')]
#[OA\Tag(name: 'Admin - Marketing', description: '营销活动')]
#[OA\Tag(name: 'Admin - System', description: '系统配置')]
#[OA\Tag(name: 'Admin - Content', description: '内容管理')]
#[OA\Tag(name: 'Admin - RBAC', description: '权限管理')]

// Seller 端
#[OA\Tag(name: 'Seller - Auth', description: '商家认证')]
#[OA\Tag(name: 'Seller - Shop', description: '店铺管理')]
#[OA\Tag(name: 'Seller - Products', description: '商品管理')]
#[OA\Tag(name: 'Seller - Orders', description: '订单处理')]
#[OA\Tag(name: 'Seller - Finance', description: '经营财务')]
#[OA\Tag(name: 'Seller - Marketing', description: '商家营销')]
#[OA\Tag(name: 'Seller - Data', description: '经营数据')]
#[OA\Tag(name: 'Seller - SubAccounts', description: '子账号管理')]

// Shop 端
#[OA\Tag(name: 'Shop - Home', description: '首页')]
#[OA\Tag(name: 'Shop - Category', description: '分类')]
#[OA\Tag(name: 'Shop - Product', description: '商品')]
#[OA\Tag(name: 'Shop - Search', description: '搜索')]
#[OA\Tag(name: 'Shop - Cart', description: '购物车')]
#[OA\Tag(name: 'Shop - Order', description: '下单支付')]
#[OA\Tag(name: 'Shop - Activity', description: '营销活动')]

// User 端
#[OA\Tag(name: 'User - Auth', description: '用户认证')]
#[OA\Tag(name: 'User - Profile', description: '个人信息')]
#[OA\Tag(name: 'User - Orders', description: '我的订单')]
#[OA\Tag(name: 'User - Wallet', description: '钱包')]
#[OA\Tag(name: 'User - Distribution', description: '分销')]
#[OA\Tag(name: 'User - Security', description: '账号安全')]

// Portal / Common
#[OA\Tag(name: 'Portal', description: '公共门户')]
#[OA\Tag(name: 'Common', description: '公共工具')]
```

**Tag 命名规范**：`{端} - {模块}`，前端 Swagger UI / Postman 可按前缀分组展示。

### 2.8 命名规范总结

| 层级 | 规范 | 示例 |
|------|------|------|
| **目录** | 大写驼峰（PascalCase） | `Admin/`, `Seller/`, `Shop/` |
| **控制器** | 大写驼峰 + `Controller` | `ProductController.php` |
| **路由文件** | 小写 + `.php` | `routes/api/admin.php` |
| **路由前缀** | 小写 + `v1` | `/api/admin/v1` |
| **路由名** | `{端}.{资源}.{动作}` | `admin.products.index`, `seller.orders.ship` |
| **Tag** | `{端} - {模块}` | `Admin - Products`, `Seller - Finance` |
| **中间件** | `{端}Authenticate` | `admin.auth`, `seller.auth` |

### 2.9 各端 FormRequest 目录结构

与控制器目录严格对应，按端分离，避免跨端混用。

```
app/Http/Requests/
├── Admin/              # 平台运营端请求校验
├── Seller/             # 商家端请求校验
├── Shop/               # C端消费者请求校验
├── User/               # 用户个人中心请求校验
├── Portal/             # 公共门户请求校验（较少）
├── Common/             # 公共工具请求校验
├── Supplier/           # 供应商端请求校验（可选）
└── BaseRequest.php     # 基础请求类（统一错误格式、分页参数提取）
```

**各端详细列表**

```
app/Http/Requests/Admin/
├── Auth/
│   ├── LoginRequest.php              # 管理员登录
│   └── RefreshTokenRequest.php     # Token刷新
├── Merchant/
│   ├── AuditRequest.php              # 商家审核通过/拒绝
│   ├── FreezeRequest.php           # 冻结商家（原因、期限）
│   └── BatchAuditRequest.php       # 批量审核
├── Product/
│   ├── AuditRequest.php              # 商品审核
│   └── ForceOfflineRequest.php     # 强制下架（原因）
├── Order/
│   └── ArbitrateRequest.php          # 订单仲裁（退款比例、责任方）
├── Refund/
│   ├── ApproveRequest.php            # 同意退款
│   └── RejectRequest.php           # 拒绝退款（原因、凭证）
├── User/
│   ├── BanRequest.php                # 封禁用户（原因、期限）
│   └── BatchBanRequest.php
├── Finance/
│   ├── SettlementConfirmRequest.php  # 确认结算
│   └── WithdrawAuditRequest.php    # 提现审核
├── Marketing/
│   ├── CouponCreateRequest.php     # 平台优惠券创建
│   └── ActivityCreateRequest.php   # 营销活动创建
├── System/
│   ├── ConfigBatchUpdateRequest.php  # 系统配置批量更新
│   └── ConfigValidateRequest.php   # 配置项校验规则
├── Content/
│   ├── BannerCreateRequest.php     # 广告位创建
│   ├── CMSCreateRequest.php        # CMS文章创建
│   └── CMSSortRequest.php          # 文章排序
├── RBAC/
│   ├── RoleCreateRequest.php       # 角色创建
│   ├── RoleSyncPermissionRequest.php # 角色同步权限
│   └── AdminCreateRequest.php      # 管理员创建
└── Report/
    └── ExportRequest.php             # 报表导出（时间范围、维度）

app/Http/Requests/Seller/
├── Auth/
│   ├── LoginRequest.php
│   ├── RegisterRequest.php           # 商家入驻（资质、证件）
│   └── ResetPasswordRequest.php
├── Shop/
│   ├── UpdateShopRequest.php         # 店铺信息更新
│   ├── AnnouncementRequest.php       # 店铺公告
│   └── FreightTemplateRequest.php  # 运费模板（按区域、重量）
├── Product/
│   ├── CreateProductRequest.php    # 商品创建（SPU+SKU+属性）
│   ├── UpdateProductRequest.php    # 商品更新
│   ├── BatchOnSaleRequest.php      # 批量上架（ID数组）
│   ├── BatchOffSaleRequest.php
│   └── BatchDeleteRequest.php
├── SKU/
│   ├── BatchUpdateRequest.php      # 批量更新SKU（价格、库存）
│   ├── UpdateStockRequest.php      # 单个SKU库存调整
│   └── UpdatePriceRequest.php      # 单个SKU价格调整
├── Order/
│   ├── ShipRequest.php             # 发货（快递单号、公司）
│   ├── BatchShipRequest.php        # 批量发货（CSV导入）
│   ├── CancelRequest.php           # 取消订单（原因）
│   └── ModifyAddressRequest.php    # 修改收货地址（发货前）
├── Refund/
│   ├── AgreeRequest.php            # 同意退款（退款金额、说明）
│   ├── RejectRequest.php           # 拒绝退款
│   └── ConfirmReceiptRequest.php   # 确认退货收到
├── Marketing/
│   ├── CreateCouponRequest.php     # 商家优惠券创建
│   ├── FullReductionRequest.php    # 满减规则设置
│   └── SeckillCreateRequest.php    # 秒杀活动创建
├── Finance/
│   └── WithdrawApplyRequest.php    # 提现申请（金额、收款账号）
├── SubAccount/
│   ├── CreateSubAccountRequest.php # 子账号创建（角色、权限）
│   └── UpdateSubAccountRequest.php
└── Settings/
    └── UpdatePasswordRequest.php   # 修改密码

app/Http/Requests/Shop/
├── Cart/
│   ├── AddCartRequest.php          # 加入购物车（sku_id、数量）
│   ├── UpdateCartRequest.php       # 更新数量
│   ├── BatchSelectRequest.php      # 批量选中/取消选中
│   └── BatchRemoveRequest.php      # 批量删除
├── Order/
│   ├── PreviewRequest.php          # 订单预览（cart_ids、address_id、coupon）
│   ├── CreateOrderRequest.php      # 创建订单（确认预览参数）
│   └── CancelRequest.php           # 取消订单（原因）
├── Payment/
│   └── PayRequest.php              # 支付（payment_method、openid）
├── Review/
│   ├── CreateReviewRequest.php     # 评价（星级、内容、图片）
│   └── AppendReviewRequest.php     # 追评
├── Address/
│   ├── CreateAddressRequest.php    # 创建地址（省市区、详细地址、姓名、电话）
│   └── UpdateAddressRequest.php
├── Coupon/
│   └── ClaimRequest.php            # 领取优惠券（coupon_id）
├── Seckill/
│   └── SeckillOrderRequest.php     # 秒杀下单（秒杀活动ID、sku_id）
└── GroupBuy/
    └── JoinGroupRequest.php          # 参与拼团（group_id）

app/Http/Requests/User/
├── Auth/
│   ├── RegisterRequest.php         # 用户注册（手机、验证码、密码）
│   ├── LoginRequest.php            # 密码登录
│   ├── LoginSmsRequest.php         # 短信验证码登录
│   ├── LoginWechatRequest.php      # 微信授权登录（code）
│   └── ForgotPasswordRequest.php   # 找回密码
├── Profile/
│   ├── UpdateProfileRequest.php    # 更新昵称、头像
│   └── UpdateAvatarRequest.php
├── Order/
│   ├── CancelRequest.php           # 取消订单
│   └── ConfirmRequest.php          # 确认收货
├── Refund/
│   ├── ApplyRefundRequest.php      # 申请退款（原因、类型、金额）
│   ├── CancelRefundRequest.php
│   └── FillLogisticsRequest.php    # 填写退货物流（快递单号）
├── Wallet/
│   ├── RechargeRequest.php         # 充值（金额、支付方式）
│   └── WithdrawRequest.php         # 提现（金额、到账方式）
├── Distribution/
│   └── WithdrawRequest.php         # 分销佣金提现
├── Security/
│   ├── UpdatePasswordRequest.php   # 修改密码（旧密码、新密码）
│   ├── BindPhoneRequest.php        # 绑定手机（验证码）
│   ├── BindEmailRequest.php
│   └── RealNameRequest.php         # 实名认证（姓名、身份证号）
└── Settings/
    └── DeactivateRequest.php         # 注销账号（原因、验证码）

app/Http/Requests/Common/
├── Upload/
│   ├── ImageUploadRequest.php      # 图片上传（file、类型、尺寸限制）
│   └── FileUploadRequest.php
├── Captcha/
│   ├── SmsSendRequest.php          # 发送短信验证码（phone、type）
│   └── VerifyRequest.php           # 校验验证码（phone、code）
├── Region/
│   └── RegionQueryRequest.php      # 省市区查询（parent_code）
└── ShortLink/
    └── CreateRequest.php             # 创建短链接（url、有效期）
```

**BaseRequest 基类**

```php
// app/Http/Requests/BaseRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
{
    /**
     * 统一响应格式，所有端复用
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'code' => 1001,
                'message' => '参数校验失败',
                'errors' => $validator->errors(),
            ], 422)
        );
    }

    /**
     * 提取分页参数（所有列表接口复用）
     */
    public function pagination(): array
    {
        return [
            'page' => (int) $this->input('page', 1),
            'per_page' => min((int) $this->input('per_page', 20), 100),
        ];
    }

    /**
     * 提取排序参数
     */
    public function orderBy(): array
    {
        $allowed = $this->allowedSortFields();
        $field = $this->input('sort_by', 'created_at');
        $direction = $this->input('sort_direction', 'desc');

        return [
            'field' => in_array($field, $allowed) ? $field : 'created_at',
            'direction' => in_array($direction, ['asc', 'desc']) ? $direction : 'desc',
        ];
    }

    /**
     * 子类覆盖，声明允许排序的字段
     */
    abstract protected function allowedSortFields(): array;
}
```

```php
// app/Http/Requests/Admin/Merchant/AuditRequest.php
namespace App\Http\Requests\Admin\Merchant;

use App\Http\Requests\BaseRequest;
use App\Enums\MerchantAuditStatus;
use Illuminate\Validation\Rule;

class AuditRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('merchants:audit') ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(MerchantAuditStatus::class)],
            'remark' => ['required_if:status,rejected', 'string', 'max:500'],
            'reject_reason' => ['required_if:status,rejected', 'string', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => '请选择审核结果',
            'remark.required_if' => '拒绝时必须填写备注',
            'reject_reason.required_if' => '请选择拒绝原因',
        ];
    }

    protected function allowedSortFields(): array
    {
        return [];
    }
}
```

---

### 2.10 模型/服务层目录与控制器对应关系

```
app/
├── Models/                    # Eloquent 模型（按模块组织，不按端）
│   ├── User.php               # 用户（多端共享：admin、seller、user）
│   ├── Merchant.php           # 商家
│   ├── Product.php            # 商品
│   ├── ProductSku.php         # SKU
│   ├── Order.php              # 订单
│   ├── OrderItem.php          # 订单项
│   ├── OrderRefund.php        # 退款/售后
│   ├── Cart.php               # 购物车
│   ├── Address.php            # 收货地址
│   ├── Coupon.php             # 优惠券
│   ├── Wallet.php             # 钱包
│   ├── WalletTransaction.php  # 钱包流水
│   ├── Settlement.php         # 结算单
│   ├── Withdraw.php           # 提现申请
│   ├── Banner.php             # 广告位
│   ├── CMSArticle.php         # CMS文章
│   ├── SystemConfig.php       # 系统配置
│   └── Concerns/              # 模型 Trait（可复用逻辑）
│       ├── HasPrice.php         # 金额处理（分转元）
│       ├── HasStock.php         # 库存管理
│       ├── HasStatus.php        # 状态枚举
│       └── BelongsToMerchant.php # 多商户字段注入
│
├── Services/                  # 领域服务层（按模块组织，不按端）
│   ├── Auth/                  # 认证服务（多端登录通用）
│   │   ├── AuthService.php    # 登录/注册/Token
│   │   └── TokenService.php   # Token生成/刷新/黑名单
│   ├── Product/               # 商品服务
│   │   ├── ProductService.php # 商品CRUD
│   │   ├── StockService.php   # 库存服务（秒杀、预扣、回滚）
│   │   └── PriceService.php   # 价格计算（优惠叠加）
│   ├── Order/                 # 订单服务
│   │   ├── OrderCreationService.php   # 创建订单（拆单、库存校验）
│   │   ├── OrderStateMachine.php      # 状态机
│   │   ├── OrderPaymentService.php    # 订单支付（支付网关选择）
│   │   ├── OrderRefundService.php     # 退款服务
│   │   └── OrderSplitService.php      # 多商户拆单
│   ├── Payment/               # 支付服务
│   │   ├── PaymentGateway.php         # 统一支付网关接口
│   │   ├── WechatPayAdapter.php     # 微信支付适配器
│   │   ├── AlipayAdapter.php        # 支付宝适配器
│   │   ├── UnionPayAdapter.php      # 银联适配器
│   │   └── ProfitSharingService.php # 分账服务
│   ├── Merchant/              # 商家服务
│   │   ├── MerchantService.php
│   │   ├── SettlementService.php    # 结算周期计算
│   │   └── WithdrawService.php      # 提现审核/转账
│   ├── User/                  # 用户服务
│   │   ├── UserService.php
│   │   ├── WalletService.php        # 钱包余额/冻结/转账
│   │   └── DistributionService.php  # 分销/佣金/提现
│   ├── Marketing/             # 营销服务
│   │   ├── CouponService.php        # 优惠券校验/核销
│   │   ├── SeckillService.php       # 秒杀（Redis Lua + 队列）
│   │   └── ActivityService.php      # 满减/满折计算
│   ├── Search/                # 搜索服务
│   │   ├── ProductSearchService.php # Elasticsearch 封装
│   │   └── SuggestService.php     # 搜索建议
│   ├── Upload/                # 上传服务
│   │   └── UploadService.php      # 本地上传/OSS直传/图片处理
│   └── Common/                # 通用服务
│       ├── RegionService.php      # 省市区
│       ├── ExpressService.php     # 物流查询
│       └── SmsService.php         # 短信发送
│
├── Repositories/              # 仓库层（数据访问抽象，可选）
│   ├── ProductRepository.php
│   ├── OrderRepository.php
│   └── UserRepository.php
│
├── Actions/                   # 单一职责 Action（Laravel 13 风格）
│   ├── Order/
│   │   ├── CreateOrderAction.php
│   │   ├── CancelOrderAction.php
│   │   └── ShipOrderAction.php
│   ├── Product/
│   │   ├── CreateProductAction.php
│   │   └── UpdateStockAction.php
│   └── User/
│       ├── RegisterUserAction.php
│       └── BindPhoneAction.php
│
└── OpenApi/                   # OpenAPI Schema 定义（13.1 章节）
    └── Schemas/
```

**多端共享 vs 端专属**

| 层级 | 组织方式 | 说明 |
|------|---------|------|
| **Models** | 按模块，不按端 | 多端共享同一数据库表 |
| **Services** | 按模块，不按端 | 业务逻辑复用，端差异通过参数控制 |
| **Repositories** | 按模块，不按端 | 数据查询复用 |
| **Actions** | 按模块，不按端 | 单一职责，可被任意端调用 |
| **Controllers** | **按端** | 负责 HTTP 层适配、参数校验、权限校验 |
| **Requests** | **按端** | 不同端校验规则不同（如 Admin 可无库存上限创建，Seller 有上限） |
| **Resources** | 按模块 + 端变体 | 同一模型不同端返回不同字段（如 Admin 返回成本价，Shop 不返回） |
| **OpenApi/Schemas** | 按模块 | 复用 Schema，不同端引用不同组合 |

**Resource 端变体示例**

```php
// app/Http/Resources/ProductResource.php（C端/Shop 端）
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->min_price,
            'market_price' => $this->max_price, // 划线价
            'cover' => $this->images[0] ?? null,
            'sales_count' => $this->sales_count,
            'skus' => ProductSkuResource::collection($this->whenLoaded('skus')),
        ];
    }
}

// app/Http/Resources/Admin/ProductResource.php（Admin 端，返回更多字段）
namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'merchant' => [
                'id' => $this->merchant_id,
                'name' => $this->merchant->name,
            ],
            'status' => $this->status,
            'cost_price' => $this->cost_price, // 成本价（仅 Admin 可见）
            'profit_margin' => $this->profit_margin,
            'audit_status' => $this->audit_status,
            'audit_remark' => $this->audit_remark,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

---

### 2.11 多端 OpenAPI 生成策略：按 Tag 分组导出，前端只生成对应端类型

**核心问题**：7 个端的控制器全部扫描后，openapi.json 文件过大（可能 5000+ 行），前端只需要自己端的类型。

**方案：按 Tag 分组过滤，各端独立生成 openapi 文件**

#### 2.11.1 后端：按 Tag 分组生成 OpenAPI

```php
// apps/backend/app/Console/Commands/GenerateOpenApiCommand.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateOpenApiCommand extends Command
{
    protected $signature = 'openapi:generate
                            {--output=../packages/api-contract/openapi.json : 输出文件路径}
                            {--tag= : 只生成指定 Tag 前缀的接口（如 Admin, Seller, Shop）}
                            {--format=json : 输出格式}
                            {--src=app : 扫描源目录}';

    public function handle(): int
    {
        $srcDir = base_path($this->option('src'));
        $outputPath = $this->resolveOutputPath();
        $tagFilter = $this->option('tag');

        try {
            $processors = [
                \App\OpenApi\Processors\FormRequestProcessor::class,
                \App\OpenApi\Processors\EnumSchemaProcessor::class,
            ];

            if ($tagFilter) {
                $processors[] = new \App\OpenApi\Processors\TagFilterProcessor($tagFilter);
            }

            $openapi = Generator::scan([$srcDir], [
                'openapi' => '3.1.0',
                'validate' => true,
                'processors' => $processors,
            ]);

            if ($openapi === null) {
                $this->error('❌ OpenAPI 生成失败');
                return self::FAILURE;
            }

            $content = $this->option('format') === 'yaml' ? $openapi->toYaml() : $openapi->toJson();

            $outputDir = dirname($outputPath);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            file_put_contents($outputPath, $content);

            $this->info("✅ OpenAPI 生成成功: {$outputPath}");
            $this->info("   路径数: " . count($openapi->paths));
            $this->info("   模型数: " . count($openapi->components->schemas ?? []));
            if ($tagFilter) {
                $this->info("   过滤 Tag: {$tagFilter}");
            }

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('❌ 生成失败: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function resolveOutputPath(): string
    {
        $output = $this->option('output');
        if (!str_starts_with($output, '/') && !str_starts_with($output, '\\')) {
            $output = base_path($output);
        }
        return $output;
    }
}
```

```php
// apps/backend/app/OpenApi/Processors/TagFilterProcessor.php
namespace App\OpenApi\Processors;

use OpenApi\Analysis;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\PathItem;

class TagFilterProcessor
{
    public function __construct(private string $tagPrefix) {}

    public function __invoke(Analysis $analysis): void
    {
        $pathsToKeep = [];
        
        foreach ($analysis->annotations as $annotation) {
            if ($annotation instanceof PathItem) {
                $methods = ['get', 'post', 'put', 'patch', 'delete', 'options', 'head'];
                $hasMatchingOperation = false;
                
                foreach ($methods as $method) {
                    $operation = $annotation->$method;
                    if ($operation instanceof Operation && $this->matchesTag($operation)) {
                        $hasMatchingOperation = true;
                    } elseif ($operation instanceof Operation) {
                        $annotation->$method = null;
                    }
                }
                
                if ($hasMatchingOperation) {
                    $pathsToKeep[] = $annotation;
                }
            }
        }
        
        if ($analysis->openapi) {
            $analysis->openapi->paths = $pathsToKeep;
        }
    }

    private function matchesTag(Operation $operation): bool
    {
        if (empty($operation->tags)) {
            return false;
        }
        
        foreach ($operation->tags as $tag) {
            if (str_starts_with($tag, $this->tagPrefix)) {
                return true;
            }
            // 公共端所有端共享
            if (in_array($this->tagPrefix, ['Admin', 'Seller', 'Shop', 'User', 'Supplier'])) {
                if ($tag === 'Common' || $tag === 'Portal') {
                    return true;
                }
            }
        }
        
        return false;
    }
}
```

**composer.json 脚本：各端独立生成**

```json
// apps/backend/composer.json
{
    "scripts": {
        "generate-api": "php artisan openapi:generate --output=../packages/api-contract/openapi.json",
        "generate-api:admin": "php artisan openapi:generate --tag=Admin --output=../packages/api-contract/openapi-admin.json",
        "generate-api:seller": "php artisan openapi:generate --tag=Seller --output=../packages/api-contract/openapi-seller.json",
        "generate-api:shop": "php artisan openapi:generate --tag=Shop --output=../packages/api-contract/openapi-shop.json",
        "generate-api:user": "php artisan openapi:generate --tag=User --output=../packages/api-contract/openapi-user.json",
        "generate-api:portal": "php artisan openapi:generate --tag=Portal --output=../packages/api-contract/openapi-portal.json",
        "generate-api:common": "php artisan openapi:generate --tag=Common --output=../packages/api-contract/openapi-common.json",
        "generate-api:all": "composer generate-api:admin && composer generate-api:seller && composer generate-api:shop && composer generate-api:user && composer generate-api:portal && composer generate-api:common"
    }
}
```

#### 2.11.2 前端：按端独立生成类型

```typescript
// scripts/generate-api-types.ts（更新版，支持多端独立生成）
import { readFileSync, writeFileSync, mkdirSync, existsSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';
import { execSync } from 'child_process';

const __dirname = dirname(fileURLToPath(import.meta.url));

const ENDPOINTS = [
    { name: 'admin', openapi: 'openapi-admin.json', package: 'api-admin' },
    { name: 'seller', openapi: 'openapi-seller.json', package: 'api-seller' },
    { name: 'shop', openapi: 'openapi-shop.json', package: 'api-shop' },
    { name: 'user', openapi: 'openapi-user.json', package: 'api-user' },
    { name: 'portal', openapi: 'openapi-portal.json', package: 'api-portal' },
    { name: 'common', openapi: 'openapi-common.json', package: 'api-common' },
];

async function main() {
    const target = process.argv[2];
    const targets = target === 'all' || !target ? ENDPOINTS : ENDPOINTS.filter(e => e.name === target);
    
    for (const endpoint of targets) {
        await generateEndpointTypes(endpoint);
    }
}

async function generateEndpointTypes(endpoint: typeof ENDPOINTS[0]): Promise<void> {
    const openapiPath = resolve(__dirname, `../packages/api-contract/${endpoint.openapi}`);
    const outputDir = resolve(__dirname, `../packages/${endpoint.package}/src`);
    
    if (!existsSync(openapiPath)) {
        console.warn(`⚠️ 跳过 ${endpoint.name}: ${openapiPath} 不存在`);
        return;
    }
    
    console.log(`🚀 生成 ${endpoint.name} 端类型...`);
    mkdirSync(outputDir, { recursive: true });
    
    execSync(
        `npx openapi-typescript ${openapiPath} --output ${outputDir}/types/api.d.ts`,
        { stdio: 'inherit' }
    );
    
    console.log(`✅ ${endpoint.name} 类型生成完成`);
}

main().catch(console.error);
```

**pnpm 脚本**

```json
// package.json
{
    "scripts": {
        "generate-api": "tsx scripts/generate-api-types.ts",
        "generate-api:admin": "tsx scripts/generate-api-types.ts admin",
        "generate-api:seller": "tsx scripts/generate-api-types.ts seller",
        "generate-api:shop": "tsx scripts/generate-api-types.ts shop",
        "generate-api:user": "tsx scripts/generate-api-types.ts user",
        "generate-api:all": "tsx scripts/generate-api-types.ts all"
    }
}
```

**各前端 package.json 只依赖自己端的契约包**

```json
// apps/admin/package.json
{
    "dependencies": {
        "@phpmall/api-admin": "workspace:*",
        "@phpmall/api-common": "workspace:*",
        "@phpmall/api-portal": "workspace:*"
    }
}

// apps/website/package.json
{
    "dependencies": {
        "@phpmall/api-shop": "workspace:*",
        "@phpmall/api-user": "workspace:*",
        "@phpmall/api-common": "workspace:*",
        "@phpmall/api-portal": "workspace:*"
    }
}

// apps/mobile/package.json
{
    "dependencies": {
        "@phpmall/api-shop": "workspace:*",
        "@phpmall/api-user": "workspace:*",
        "@phpmall/api-common": "workspace:*",
        "@phpmall/api-portal": "workspace:*"
    }
}
```

**Monorepo 包结构**

```
packages/
├── api-contract/          # 完整 OpenAPI（所有端，用于 Swagger UI 展示）
│   ├── openapi.json
│   └── src/
│
├── api-admin/           # 平台运营端契约（Admin + Common + Portal）
│   ├── openapi-admin.json
│   └── src/
│       ├── types/api.d.ts
│       ├── schemas/
│       └── client/
│
├── api-seller/          # 商家端契约（Seller + Common + Portal）
│   ├── openapi-seller.json
│   └── src/
│
├── api-shop/            # C端商城契约（Shop + Common + Portal）
│   ├── openapi-shop.json
│   └── src/
│
├── api-user/            # 用户端契约（User + Common + Portal）
│   ├── openapi-user.json
│   └── src/
│
├── api-portal/          # 公共门户契约（Portal + Common）
│   └── src/
│
└── api-common/          # 公共工具契约（Common）
    └── src/
```

#### 2.11.3 完整开发工作流

```bash
# 1. 后端修改接口后，生成各端独立 OpenAPI
cd apps/backend
composer generate-api:all

# 2. 前端重新生成自己端的类型
cd ../../
pnpm generate-api:all

# 3. 或只生成特定端（如 PC 商城只依赖 Shop + User + Common + Portal）
pnpm generate-api:shop && pnpm generate-api:user && pnpm generate-api:common && pnpm generate-api:portal

# 4. Turborepo 自动检测到 api-shop / api-user 变更，重新构建 pc-mall
pnpm turbo run build --filter=pc-mall...
```

**方案对比**

| 方案 | 文件大小 | 构建速度 | 类型安全 | 适用场景 |
|------|---------|---------|---------|----------|
| **单一 openapi.json** | 大（5000+ 行） | 慢（全量生成） | 中（端类型混杂） | 小型项目，端少 |
| **按端独立 openapi（推荐）** | 小（每端 500-1500 行） | 快（并行生成） | 高（无冗余类型） | **中大型 B2B2C** |
| **前端过滤（不推荐）** | 大（传输全量） | 慢 | 低（构建时过滤，易漏） | 不推荐 |

> **文档结束**  
> 本文档应随项目迭代持续更新，建议在每次架构变更或技术升级后同步修订。


---

## 3. 环境搭建指南

### 3.1 开发环境要求

| 组件 | 版本 | 说明 |
|------|------|------|
| PHP | 8.4 | 主运行环境 |
| Composer | 2.7+ | PHP 依赖管理 |
| Node.js | 22+ | 前端构建工具 |
| pnpm | 9+ | 包管理器（Monorepo） |
| Docker | 24+ | 容器化环境 |
| Docker Compose | 2.20+ | 本地开发环境编排 |

### 3.2 本地开发环境启动

```bash
# 1. 克隆代码仓库
git clone <repo-url>
cd phpmall

# 2. 安装 PHP 依赖
cd apps/backend
composer install
cd ../..

# 3. 安装前端依赖
pnpm install

# 4. 启动本地开发环境（Docker Compose）
docker-compose -f docker-compose.dev.yml up -d
# 包含：MySQL 8.4, Redis 8.8, Elasticsearch, RabbitMQ, MinIO (S3)

# 5. 初始化数据库
php artisan migrate:fresh --seed

# 6. 启动后端开发服务器（Octane）
cd apps/backend
php artisan octane:start --watch

# 7. 启动前端开发服务器
pnpm dev:admin    # 管理后台
pnpm dev:pc       # PC 商城
pnpm dev:h5       # H5 商城
```

### 3.3 环境变量配置

```bash
# .env (本地开发)
APP_NAME=PHPMall
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=phpmall
DB_USERNAME=root
DB_PASSWORD=secret

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

ES_HOST=127.0.0.1:9200
RABBITMQ_HOST=127.0.0.1
RABBITMQ_PORT=5672
RABBITMQ_USER=guest
RABBITMQ_PASSWORD=guest

WECHAT_PAY_MCH_ID=xxx
WECHAT_PAY_APP_ID=xxx
ALIPAY_APP_ID=xxx
```

---

## 4. 部署流程

### 4.1 部署架构

```
生产环境：
+---------------------------------------------------+
|  负载均衡（Nginx / AWS ALB）                      |
|  SSL 终止、流量分发、健康检查                     |
+--------------+------------------+-----------------+
               |                  |
+--------------v--+   +-----------v----------+
|  App Server 1    |   |  App Server 2         |
|  Laravel Octane  |   |  Laravel Octane       |
|  (Swoole)        |   |  (Swoole)             |
+--------------+--+   +-----------+----------+
               |                  |
               +----------+-------+
                          |
+-------------------------v-------------------------+
|  数据层                                            |
|  MySQL 8.4 主从集群 / Redis 8.8 集群             |
|  Elasticsearch 集群 / MongoDB 副本集               |
|  RabbitMQ 集群 / MinIO 对象存储                    |
+---------------------------------------------------+
```

### 4.2 CI/CD 流程

```yaml
# .github/workflows/deploy.yml 简化示意
name: Deploy

on:
  push:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.4
      - name: Install dependencies
        run: composer install && pnpm install
      - name: Run tests
        run: php artisan test && pnpm test
      - name: Build frontend
        run: pnpm build

  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to production
        run: |
          ssh deploy@server "cd /var/www/phpmall && git pull && composer install && php artisan migrate && php artisan octane:reload && pnpm build"
```

### 4.3 发布 checklist

- [ ] 代码合并到 main 分支
- [ ] CI/CD 流水线全部通过（测试、构建、扫描）
- [ ] 数据库迁移脚本已准备并回滚方案已确认
- [ ] 配置变更已同步（环境变量、Nginx 配置）
- [ ] 灰度发布（金丝雀）完成，监控无异常
- [ ] 全量发布完成，监控 30 分钟无异常
- [ ] 发布后验证（核心流程走通）

---

## 5. 代码规范

### 5.1 PHP 代码规范（基于 PSR-12）

```php
// 命名规范
class OrderService          // 类名：PascalCase
interface PaymentGateway    // 接口名：PascalCase + 形容词/能力
{
    public function pay(array $params): PaymentResult;  // 方法名：camelCase
}

const MAX_RETRY_TIMES = 3;  // 常量：UPPER_SNAKE_CASE

$orderItems = [];           // 变量：camelCase
$isPaid = false;            // 布尔：is/has/can 前缀
```

### 5.2 前端代码规范

```typescript
// 组件命名：PascalCase
// ProductCard.tsx, OrderDetail.tsx
// 变量命名：camelCase
// 常量命名：UPPER_SNAKE_CASE
// 类型命名：PascalCase + Type 后缀
```

### 5.3 Git 提交规范

```
类型(范围): 简要描述

类型：
- feat: 新功能
- fix: 修复
- docs: 文档
- style: 格式
- refactor: 重构
- perf: 性能优化
- test: 测试
- chore: 构建/工具
- ci: CI/CD
```

> **文档结束**  
> 本文档应随项目进展持续更新。

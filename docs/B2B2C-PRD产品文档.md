# B2B2C 商城系统 PRD 产品文档

> **文档版本**：v2.0  
> **编写日期**：2026年6月  
> **适用项目**：PHP B2B2C 多商户电商平台  
> **目标读者**：产品经理、UI/UX 设计师、前端开发工程师、项目经理

---

## 目录

1. [产品概述](#1-产品概述)
2. [用户角色与使用场景](#2-用户角色与使用场景)
3. [多端产品设计](#3-多端产品设计)
4. [功能模块详细设计](#4-功能模块详细设计)
5. [交互流程与原型](#5-交互流程与原型)
6. [非功能性需求（产品侧）](#6-非功能性需求产品侧)
7. [前端与多端方案](#7-前端与多端方案)
8. [B2B2C 核心模块架构](#8-b2b2c-核心模块架构)

---


## 1. 产品概述

### 1.1 产品定位

PHPMall 是一款面向中大型电商场景的 **B2B2C 多商户电商平台**。平台提供基础设施和流量入口，商家入驻后拥有独立店铺，自主管理商品和订单。产品覆盖 PC 商城、Mobile 移动端（H5/小程序/App）、商家后台、平台管理后台、供应商后台五个终端。

### 1.2 产品目标

| 阶段 | 目标 | 里程碑 |
|------|------|--------|
| MVP | 核心交易闭环 | 商家入驻 → 商品发布 → 用户下单 → 支付 → 发货 → 收货 |
| V1.0 | 完整电商能力 | 营销（优惠券/秒杀/满减）、分销、售后、对账、钱包 |
| V1.5 | 规模化运营 | 多级分销、数据大屏、智能推荐、多语言 |
| V2.0 | 生态化 | 供应商体系、开放 API、插件市场 |

### 1.3 竞品对比

| 能力 | PHPMall | 有赞 | 微盟 | ShopXO |
|------|---------|------|------|--------|
| 多商户入驻 | ✅ | ✅ | ✅ | ✅ |
| 三级分销 | ✅ | ✅（付费） | ✅（付费） | ❌ |
| SPU-SKU 模型 | ✅ | ✅ | ✅ | ✅ |
| 源码交付 | ✅ | ❌（SaaS） | ❌（SaaS） | ✅ |
| RBAC 子账号 | ✅ | ✅ | ✅ | ❌ |
| 虚拟钱包 | ✅ | ✅ | ✅ | ❌ |

---

## 2. 用户角色与使用场景

### 2.1 角色画像

| 角色 | 典型画像 | 核心关注点 |
|------|----------|-----------|
| **买家（消费者）** | 25-45 岁，网购习惯用户 | 商品丰富度、价格竞争力、物流时效、售后保障 |
| **商家主账号** | 品牌方/经销商/个体户 | 入驻门槛、流量获取、订单管理效率、结算周期 |
| **商家子账号** | 客服/仓管/运营人员 | 操作便捷性、权限清晰、通知及时 |
| **平台运营** | 电商运营团队 | 商家质量管控、平台收益、数据报表、风控 |
| **分销员** | 兼职/全职推广者 | 佣金比例、提现门槛、推广素材获取 |

### 2.2 核心使用场景

**场景一：买家首次购物**
> 用户通过微信分享链接进入 H5 商城 → 浏览首页推荐商品 → 搜索「连衣裙」→ 按价格筛选 → 查看商品详情（选择尺码/颜色 SKU）→ 加入购物车 → 继续浏览 → 去结算 → 选择收货地址 → 使用优惠券 → 微信支付 → 支付成功 → 等待发货

**场景二：商家入驻发布商品**
> 商家注册账号 → 提交营业执照、法人身份证 → 平台审核通过 → 开通店铺 → 设置店铺信息 → 创建商品（标题/描述/图片/规格 SKU/价格）→ 提交审核 → 平台审核通过 → 商品上架 → C 端可见

**场景三：售后维权**
> 买家收货后发现瑕疵 → 申请退货退款 → 上传凭证照片 → 商家审核 → 同意 → 买家寄回商品 → 填写退货物流 → 商家收货确认 → 平台退款到原支付渠道

---

## 3. 多端产品设计

### 3.1 终端矩阵

| 终端 | 应用目录 | 技术方案 | 目标用户 | 核心场景 |
|------|----------|----------|----------|----------|
| PC 商城 | `apps/website` | React 19 + Next.js 16 (SSR) | 桌面端买家 | 搜索浏览、详情查看、下单 |
| Mobile 移动端 | `apps/mobile` | UniApp 3 (H5/小程序/App) | 移动端买家 | 分享裂变、快速下单、小程序、App |
| 商家后台 | `apps/seller` | React 19 + Ant Design 6 | 商家/子账号 | 商品管理、订单处理 |
| 平台管理后台 | `apps/admin` | React 19 + Ant Design 6 | 平台运营 | 商家审核、数据报表 |
| 供应商后台 | `apps/supplier` | React 19 + Ant Design 6 | 供应链供应商 | 供货商品、采购订单、对账 |

### 3.2 响应式设计原则

- 移动端优先（Mobile First）
- PC 商城采用 SSR 保证首屏速度和 SEO
- Mobile 移动端（`apps/mobile`）一套 UniApp 代码库输出 H5、微信小程序、App 三端，平台特化逻辑放在 `src/platforms/` 目录
- 商家后台、管理后台、供应商后台均为桌面端 Web 应用，不要求移动端适配

---

## 4. 功能模块详细设计

### 4.1 买家端（商城）功能树

```
商城首页
├── 搜索（关键词 + 历史 + 热门推荐）
├── Banner 轮播
├── 商品分类导航（三级分类）
├── 推荐商品（热销/新品/精选）
└── 底部导航（首页/分类/购物车/我的）

商品列表
├── 分类筛选（侧边栏）
├── 排序（综合/销量/价格/新品）
├── 价格区间筛选
├── 属性筛选（品牌/规格）
└── 商品卡片（图/名/价/销量）

商品详情
├── 图片轮播（主图 + SKU 图）
├── 价格展示（售价 + 划线价）
├── 规格选择（SKU 联动库存/价格）
├── 数量加减
├── 加入购物车 / 立即购买
├── 商品介绍（富文本）
└── 商品评价（星级 + 图文）

购物车
├── 按商家分组展示
├── 全选/单选
├── 修改数量
├── 删除商品
├── 价格汇总（含优惠提示）
└── 去结算

我的
├── 订单中心（待付款/待发货/待收货/已完成/售后）
├── 优惠券（可用/已用/已过期）
├── 收货地址管理
├── 钱包（余额/明细）
├── 分销中心（我的推广）
└── 设置（修改密码/关于）
```

### 4.2 商家后台功能树

```
商家后台
├── 数据概览（今日订单/销售额/访客）
├── 商品管理（列表/发布/编辑/上下架）
├── 订单管理（列表/发货/备注/导出）
├── 售后管理（退款/退货/仲裁中）
├── 评价管理
├── 营销中心（优惠券/满减）
├── 财务管理（对账单/提现申请）
├── 店铺设置（基础信息/运费模板）
├── 子账号管理（创建/权限分配）
└── 消息通知（平台通知/系统消息）
```

### 4.3 平台管理后台功能树

```
平台管理后台
├── 数据仪表盘（GMV/订单/用户/商家）
├── 商家管理（列表/入驻审核/冻结/结算）
├── 商品管理（列表/审核/强制下架）
├── 订单管理（全平台查看/仲裁）
├── 营销中心（优惠券/秒杀/满减创建）
├── 分销管理（分销员审核/佣金管理）
├── 财务管理（对账/分账/提现审核）
├── 系统设置（管理员/角色/参数配置）
├── 内容管理（Banner/文章/公告）
└── 操作日志
```

---

## 5. 交互流程与原型

### 5.1 核心页面交互准则

| 交互原则 | 说明 |
|----------|------|
| 即时反馈 | 操作后立即有视觉反馈（loading/success/error） |
| 容错设计 | 关键操作有二次确认弹窗（删除、取消订单） |
| 空状态 | 所有列表空态有引导（暂无订单→去逛逛） |
| 防抖截流 | 支付/提交按钮防重复点击，倒计时防刷 |
| 渐进披露 | 复杂表单分步填写，高级选项默认折叠 |
| 断点续传 | 网络异常时自动重试，表单内容不丢失 |

### 5.2 状态流转交互说明

**订单列表 Tab 切换（买家视角）**：
- 待付款 → 显示倒计时 + "去支付"按钮
- 待发货 → 显示"提醒发货"（24h 后可用）
- 待收货 → 显示物流进度条 + "确认收货"按钮
- 已完成 → 显示"去评价"/"再次购买"
- 售后中 → 显示售后进度（商家处理中/退货中/退款中）

---

## 6. 非功能性需求（产品侧）

### 6.1 用户体验指标

| 指标 | 目标值 | 测量方式 |
|------|--------|----------|
| 新用户注册转化率 | ≥ 60% | 注册完成 / 进入注册页 |
| 首单转化率 | ≥ 15% | 首单用户 / 注册用户 |
| 支付成功率 | ≥ 98% | 支付成功 / 发起支付 |
| 页面可用率 | ≥ 99.9% | 监控周期内无 500 错误 |
| 客服响应率 | 15 分钟内 | 首次回复时间 |

### 6.2 无障碍设计

- 颜色对比度满足 WCAG AA 级
- 关键按钮区域 ≥ 44x44px（移动端）
- 支持屏幕阅读器访问（语义化 HTML + aria 标签）
- 键盘导航支持（Tab/Enter/Esc）

---

## 7. 前端与多端方案

### 7.1 PC 商城：React 19 + Next.js 16

```
┌─────────────────────────────────────────────┐
│  PC 商城 (Next.js 16 App Router)            │
├─────────────────────────────────────────────┤
│  ┌─────────────────────────────────────┐   │
│  │  pages/                             │   │
│  │  ├─ (home)/         首页            │   │
│  │  ├─ category/       分类列表        │   │
│  │  ├─ product/[id]/   商品详情（SSR）   │   │
│  │  ├─ cart/           购物车           │   │
│  │  ├─ checkout/       结算页            │   │
│  │  ├─ order/          订单中心          │   │
│  │  ├─ user/           个人中心          │   │
│  │  └─ search/         搜索结果          │   │
│  └─────────────────────────────────────┘   │
│  ┌─────────────────────────────────────┐   │
│  │  components/                        │   │
│  │  ├─ ProductCard/      商品卡片       │   │
│  │  ├─ ProductList/      商品列表       │   │
│  │  ├─ FilterPanel/      筛选面板       │   │
│  │  ├─ CartItem/         购物车项       │   │
│  │  └─ PaymentModal/     支付弹窗       │   │
│  └─────────────────────────────────────┘   │
│  ┌─────────────────────────────────────┐   │
│  │  lib/                               │   │
│  │  ├─ api.ts           API 封装（SWR）  │   │
│  │  ├─ auth.ts          鉴权逻辑        │   │
│  │  └─ utils.ts         工具函数        │   │
│  └─────────────────────────────────────┘   │
└─────────────────────────────────────────────┘
```

**关键配置**：

```typescript
// next.config.js
const nextConfig = {
  output: 'standalone', // Docker 独立部署
  images: {
    domains: ['oss.example.com', 'cdn.example.com'],
    remotePatterns: [
      { protocol: 'https', hostname: '**.alicdn.com' },
    ],
  },
  rewrites: async () => [
    // API 代理到后端
    { source: '/api/:path*', destination: `${process.env.API_BASE_URL}/:path*` },
  ],
  headers: async () => [
    {
      source: '/:path*',
      headers: [
        { key: 'X-Frame-Options', value: 'SAMEORIGIN' },
        { key: 'X-Content-Type-Options', value: 'nosniff' },
      ],
    },
  ],
};
```

### 7.2 Mobile 移动端：UniApp 3（H5 / 微信小程序 / App）

```
┌─────────────────────────────────────────────┐
│  UniApp 3 项目结构                           │
├─────────────────────────────────────────────┤
│  ┌─────────────────────────────────────┐   │
│  │  pages/                             │   │
│  │  ├─ index/          首页             │   │
│  │  ├─ category/      分类              │   │
│  │  ├─ product/        商品详情          │   │
│  │  ├─ cart/           购物车            │   │
│  │  ├─ order/          订单              │   │
│  │  ├─ user/           我的              │   │
│  │  └─ login/          登录              │   │
│  └─────────────────────────────────────┘   │
│  ┌─────────────────────────────────────┐   │
│  │  components/                        │   │
│  │  ├─ ProductGrid/    商品网格         │   │
│  │  ├─ SkuSelector/    SKU 选择器       │   │
│  │  └─ Countdown/      秒杀倒计时       │   │
│  └─────────────────────────────────────┘   │
│  ┌─────────────────────────────────────┐   │
│  │  utils/                             │   │
│  │  ├─ request.ts     请求封装          │   │
│  │  ├─ storage.ts     本地存储          │   │
│  │  └─ payment.ts     支付统一封装       │   │
│  └─────────────────────────────────────┘   │
│  ┌─────────────────────────────────────┐   │
│  │  platforms/                         │   │
│  │  ├─ weixin/        微信小程序特殊逻辑 │   │
│  │  ├─ h5/            H5 特殊逻辑       │   │
│  │  └─ app/           App 特殊逻辑       │   │
│  └─────────────────────────────────────┘   │
└─────────────────────────────────────────────┘
```

### 7.3 管理后台：React 19 + Ant Design 6 + Vite 8.1

```typescript
// 管理后台路由结构（基于 Ant Design Pro）
const routes = [
  {
    path: '/dashboard',
    name: '数据概览',
    icon: 'Dashboard',
    component: './Dashboard',
  },
  {
    path: '/merchant',
    name: '商家管理',
    icon: 'Shop',
    routes: [
      { path: 'list', name: '商家列表', component: './Merchant/List' },
      { path: 'audit', name: '入驻审核', component: './Merchant/Audit' },
      { path: 'settlement', name: '结算管理', component: './Merchant/Settlement' },
    ],
  },
  {
    path: '/product',
    name: '商品管理',
    icon: 'Shopping',
    routes: [
      { path: 'list', name: '商品列表', component: './Product/List' },
      { path: 'category', name: '分类管理', component: './Product/Category' },
      { path: 'brand', name: '品牌管理', component: './Product/Brand' },
    ],
  },
  {
    path: '/order',
    name: '订单管理',
    icon: 'FileText',
    routes: [
      { path: 'list', name: '订单列表', component: './Order/List' },
      { path: 'refund', name: '售后退款', component: './Order/Refund' },
      { path: 'delivery', name: '发货管理', component: './Order/Delivery' },
    ],
  },
  {
    path: '/marketing',
    name: '营销中心',
    icon: 'Gift',
    routes: [
      { path: 'coupon', name: '优惠券', component: './Marketing/Coupon' },
      { path: 'seckill', name: '秒杀活动', component: './Marketing/Seckill' },
      { path: 'discount', name: '满减满折', component: './Marketing/Discount' },
    ],
  },
  {
    path: '/finance',
    name: '财务管理',
    icon: 'DollarCircle',
    routes: [
      { path: 'overview', name: '资金概览', component: './Finance/Overview' },
      { path: 'reconciliation', name: '对账管理', component: './Finance/Reconciliation' },
      { path: 'withdraw', name: '提现审核', component: './Finance/Withdraw' },
    ],
  },
  {
    path: '/distribution',
    name: '分销管理',
    icon: 'ShareAlt',
    routes: [
      { path: 'distributor', name: '分销员', component: './Distribution/Distributor' },
      { path: 'commission', name: '佣金管理', component: './Distribution/Commission' },
    ],
  },
  {
    path: '/system',
    name: '系统设置',
    icon: 'Setting',
    routes: [
      { path: 'user', name: '管理员', component: './System/User' },
      { path: 'role', name: '角色权限', component: './System/Role' },
      { path: 'config', name: '参数配置', component: './System/Config' },
      { path: 'log', name: '操作日志', component: './System/Log' },
    ],
  },
];
```

### 7.4 商家后台：React 19 + Ant Design 6 + Vite 8.1

对应应用目录 `apps/seller`，面向入驻商家及子账号，提供商品发布、订单处理、库存管理、营销工具、数据报表、结算提现等能力。路由与权限模型与平台管理后台类似，但数据范围限定在当前商家。

### 7.5 供应商后台：React 19 + Ant Design 6 + Vite 8.1

对应应用目录 `apps/supplier`，面向供应链供应商（可选），提供供货商品管理、采购订单处理、仓库库存、供货发货、对账结算等能力。

---


---

## 8. B2B2C 核心模块架构

### 8.1 多租户（多商户）架构

#### 方案对比

| 方案 | 实现方式 | 优点 | 缺点 | 适用规模 |
|------|----------|------|------|----------|
| **字段隔离** | 所有表加 `merchant_id` | 简单、成本低、跨商户查询方便 | 数据量大时性能下降、隔离性弱 | 中小型 |
| **Schema 隔离** | 每个商户独立 Schema | 数据隔离性好、可独立备份 | 跨商户查询复杂、维护成本高 | 中大型 |
| **数据库隔离** | 每个商户独立数据库 | 最高隔离性、可独立扩容 | 成本极高、运维复杂 | 超大型/定制 |

**推荐方案：字段隔离（Schema 隔离作为未来扩展点）**

```php
// 全局商户 ID 注入（Middleware）
class MerchantContextMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $merchantId = $request->header('X-Merchant-Id') 
            ?? $request->route('merchant_id') 
            ?? session('merchant_id');
        
        if ($merchantId) {
            MerchantContext::set($merchantId);
            
            // 全局 Scope 自动过滤
            Model::addGlobalScope('merchant', function (Builder $builder) use ($merchantId) {
                if (in_array('merchant_id', $builder->getModel()->getFillable())) {
                    $builder->where('merchant_id', $merchantId);
                }
            });
        }
        
        return $next($request);
    }
}

// MerchantContext 上下文
class MerchantContext
{
    private static ?int $merchantId = null;
    
    public static function set(int $id): void
    {
        self::$merchantId = $id;
    }
    
    public static function get(): ?int
    {
        return self::$merchantId;
    }
    
    public static function clear(): void
    {
        self::$merchantId = null;
    }
}
```

### 8.2 RBAC 权限体系

```
权限模型：RBAC + 数据权限

┌─────────────────────────────────────────┐
│  用户（User）                            │
│  ├─ 平台管理员（super_admin）            │
│  ├─ 运营人员（operator）                 │
│  ├─ 商家主账号（merchant_owner）          │
│  ├─ 商家子账号（merchant_staff）          │
│  └─ 分销员（distributor）                 │
└─────────────────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────┐
│  角色（Role）                            │
│  ├─ 平台：商品管理员、订单管理员、财务    │
│  ├─ 商家：商品编辑、订单处理、客服       │
│  └─ 数据权限：仅看自己 / 看全店 / 看平台  │
└─────────────────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────┐
│  权限（Permission）                      │
│  ├─ product:create, product:edit      │
│  ├─ order:view, order:ship, order:refund│
│  ├─ merchant:view, merchant:audit     │
│  └─ finance:view, finance:withdraw:audit│
└─────────────────────────────────────────┘
```

```php
// 使用 spatie/laravel-permission
// 商家子账号权限示例
$role = Role::create(['name' => 'merchant:staff', 'guard_name' => 'merchant']);
$role->givePermissionTo([
    'product:view', 'product:create', 'product:edit',
    'order:view', 'order:ship', 'order:refund',
    'merchant:view', 'merchant:edit_profile',
]);

// 数据权限中间件
class DataScopeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // 平台管理员可看所有数据
        if ($user->hasRole('super_admin')) {
            DataScope::set(DataScope::ALL);
        }
        // 商家主账号看全店数据
        elseif ($user->hasRole('merchant_owner')) {
            DataScope::set(DataScope::MERCHANT, $user->merchant_id);
        }
        // 子账号仅看自己的数据
        else {
            DataScope::set(DataScope::SELF, $user->id);
        }
        
        return $next($request);
    }
}
```

### 8.3 商品体系：SPU-SKU 模型

```
┌─────────────────────────────────────────────┐
│  SPU（Standard Product Unit）              │
│  ├─ id: 1001                                │
│  ├─ title: "iPhone 15 Pro"                  │
│  ├─ category_id: 100                        │
│  ├─ brand_id: 50                            │
│  ├─ description: HTML 富文本               │
│  ├─ images: [url1, url2, url3]             │
│  ├─ merchant_id: 200                        │
│  └─ status: on_sale                         │
│                                             │
│  规格属性（SpuAttribute）                    │
│  ├─ 颜色: [黑色, 白色, 原色钛金属]            │
│  └─ 存储容量: [128GB, 256GB, 512GB, 1TB]    │
│                                             │
│  SKU（Stock Keeping Unit）                   │
│  ├─ id: 100101 (黑色+128GB)                  │
│  ├─ sku_code: "SKU-100101"                  │
│  ├─ price: 7999                             │
│  ├─ stock: 100                              │
│  ├─ specs: {"颜色": "黑色", "存储容量": "128GB"}│
│  └─ image: 黑色 SKU 图片                     │
│  ├─ id: 100102 (黑色+256GB)                  │
│  ├─ price: 8999                             │
│  └─ stock: 50                               │
└─────────────────────────────────────────────┘
```

```php
// 数据库迁移
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('merchant_id')->index();
    $table->foreignId('category_id')->index();
    $table->foreignId('brand_id')->nullable();
    $table->string('title');
    $table->string('subtitle')->nullable();
    $table->text('description')->nullable();
    $table->json('images');
    $table->json('attributes'); // [{"id": 1, "name": "颜色", "values": ["黑色", "白色"]}]
    $table->decimal('min_price', 10, 2)->default(0); // SKU 最低售价
    $table->decimal('max_price', 10, 2)->default(0); // SKU 最高售价
    $table->unsignedInteger('total_stock')->default(0); // SKU 库存合计
    $table->unsignedInteger('sales_count')->default(0);
    $table->tinyInteger('status')->default(0)->comment('0:草稿 1:上架 2:下架');
    $table->timestamps();
    
    $table->index(['status', 'merchant_id']);
    $table->index(['category_id', 'status']);
});

Schema::create('product_skus', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->index();
    $table->string('sku_code')->unique();
    $table->json('specs'); // {"颜色": "黑色", "存储容量": "128GB"}
    $table->decimal('price', 10, 2);
    $table->decimal('market_price', 10, 2)->nullable(); // 划线价
    $table->unsignedInteger('stock')->default(0);
    $table->string('image')->nullable(); // SKU 专属图片
    $table->string('barcode')->nullable();
    $table->tinyInteger('status')->default(1);
    $table->timestamps();
    
    $table->index(['product_id', 'status']);
});
```

### 8.4 订单状态机

```
┌─────────────────────────────────────────────────────────────────────┐
│                        订单状态机（State Machine）                    │
└─────────────────────────────────────────────────────────────────────┘

[待付款] ──pay()──> [待发货] ──ship()──> [待收货] ──receive()──> [已完成]
    │                  │                    │
    │ cancel()         │ refund()           │ refund()
    ▼                  ▼                    ▼
[已取消]           [售后中]              [售后中]
    │                  │                    │
    │                  │ resolve()          │ resolve()
    │                  ▼                    ▼
    │               [已退款]             [已退款/换货完成]
    │
    └── 超时自动取消（30分钟）

关键规则：
- 待付款 → 只能取消或支付
- 待发货 → 可发货或全额退款
- 待收货 → 可确认收货或申请售后
- 已完成 → 仅支持售后（7天内）
```

```php
// 订单状态机实现
enum OrderStatus: int
{
    case PENDING_PAYMENT = 10;   // 待付款
    case PAID = 20;              // 已支付（内部状态，外部展示为待发货）
    case PENDING_SHIPMENT = 30;  // 待发货
    case SHIPPED = 40;          // 已发货
    case PENDING_RECEIPT = 50;   // 待收货
    case RECEIVED = 60;         // 已收货（内部状态）
    case COMPLETED = 70;        // 已完成
    case CANCELLED = 80;        // 已取消
    case REFUNDING = 90;        // 退款中
    case REFUNDED = 100;        // 已退款
    
    public function canTransitionTo(self $newStatus): bool
    {
        return match($this) {
            self::PENDING_PAYMENT => in_array($newStatus, [self::PAID, self::CANCELLED]),
            self::PAID => in_array($newStatus, [self::PENDING_SHIPMENT, self::REFUNDING]),
            self::PENDING_SHIPMENT => in_array($newStatus, [self::SHIPPED, self::REFUNDING]),
            self::SHIPPED => in_array($newStatus, [self::PENDING_RECEIPT, self::REFUNDING]),
            self::PENDING_RECEIPT => in_array($newStatus, [self::RECEIVED, self::REFUNDING]),
            self::RECEIVED => in_array($newStatus, [self::COMPLETED, self::REFUNDING]),
            self::COMPLETED => in_array($newStatus, [self::REFUNDING]),
            self::REFUNDING => in_array($newStatus, [self::REFUNDED]),
            default => false,
        };
    }
    
    public function getLabel(): string
    {
        return match($this) {
            self::PENDING_PAYMENT => '待付款',
            self::PAID => '已支付',
            self::PENDING_SHIPMENT => '待发货',
            self::SHIPPED => '已发货',
            self::PENDING_RECEIPT => '待收货',
            self::RECEIVED => '已收货',
            self::COMPLETED => '已完成',
            self::CANCELLED => '已取消',
            self::REFUNDING => '退款中',
            self::REFUNDED => '已退款',
        };
    }
}

class OrderStateMachine
{
    public function transition(Order $order, OrderStatus $newStatus, array $context = []): void
    {
        $currentStatus = $order->status;
        
        if (!$currentStatus->canTransitionTo($newStatus)) {
            throw new InvalidStateTransitionException(
                "订单状态无法从 {$currentStatus->getLabel()} 变更为 {$newStatus->getLabel()}"
            );
        }
        
        DB::transaction(function () use ($order, $newStatus, $context) {
            // 记录状态变更日志
            OrderStatusLog::create([
                'order_id' => $order->id,
                'from_status' => $order->status,
                'to_status' => $newStatus,
                'operator_id' => $context['operator_id'] ?? null,
                'operator_type' => $context['operator_type'] ?? 'system',
                'remark' => $context['remark'] ?? null,
            ]);
            
            // 更新订单状态
            $order->update(['status' => $newStatus]);
            
            // 触发对应事件
            match($newStatus) {
                OrderStatus::PAID => event(new OrderPaid($order)),
                OrderStatus::SHIPPED => event(new OrderShipped($order)),
                OrderStatus::COMPLETED => event(new OrderCompleted($order)),
                OrderStatus::CANCELLED => event(new OrderCancelled($order)),
                OrderStatus::REFUNDED => event(new OrderRefunded($order)),
                default => null,
            };
        });
    }
}
```

### 8.5 拆单逻辑（多商家订单）

```
用户订单（父订单）                    商家订单（子订单）
┌─────────────────────┐           ┌─────────────────────┐
│  order_no: P20240001│           │  order_no: S20240001A │
│  user_id: 100       │           │  merchant_id: 10      │
│  total_amount: 500  │    ┌─────>│  parent_id: 1         │
│  status: 待付款      │    │      │  amount: 200          │
│  is_split: true     │    │      │  status: 待付款        │
└─────────────────────┘    │      └─────────────────────┘
                           │
                           │      ┌─────────────────────┐
                           │      │  order_no: S20240001B │
                           └─────>│  merchant_id: 20      │
                                  │  parent_id: 1         │
                                  │  amount: 300          │
                                  │  status: 待付款        │
                                  └─────────────────────┘

拆单规则：
1. 同商家商品合并为一个子订单
2. 子订单独立支付/发货/退款
3. 父订单状态 = 所有子订单状态聚合
```

```php
class OrderSplitService
{
    /**
     * 按商家拆单
     */
    public function split(Order $parentOrder): array
    {
        $items = $parentOrder->items;
        
        // 按 merchant_id 分组
        $grouped = $items->groupBy('merchant_id');
        
        $subOrders = [];
        
        foreach ($grouped as $merchantId => $merchantItems) {
            $subOrder = Order::create([
                'parent_id' => $parentOrder->id,
                'order_no' => $this->generateSubOrderNo($parentOrder->order_no, $merchantId),
                'user_id' => $parentOrder->user_id,
                'merchant_id' => $merchantId,
                'total_amount' => $merchantItems->sum('total'),
                'freight_amount' => $this->calculateFreight($merchantItems),
                'status' => OrderStatus::PENDING_PAYMENT,
                'address' => $parentOrder->address,
            ]);
            
            // 复制订单项
            foreach ($merchantItems as $item) {
                $subOrder->items()->create([
                    'product_id' => $item->product_id,
                    'sku_id' => $item->sku_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);
            }
            
            $subOrders[] = $subOrder;
        }
        
        // 更新父订单为已拆单
        $parentOrder->update(['is_split' => true]);
        
        return $subOrders;
    }
    
    /**
     * 聚合子订单状态到父订单
     */
    public function aggregateStatus(Order $parentOrder): void
    {
        $subStatuses = $parentOrder->subOrders->pluck('status')->unique();
        
        // 所有子订单状态一致
        if ($subStatuses->count() === 1) {
            $parentOrder->update(['status' => $subStatuses->first()]);
            return;
        }
        
        // 有子订单完成，父订单完成
        if ($subStatuses->contains(OrderStatus::COMPLETED)) {
            $allCompleted = $parentOrder->subOrders->every(fn($o) => $o->status === OrderStatus::COMPLETED);
            if ($allCompleted) {
                $parentOrder->update(['status' => OrderStatus::COMPLETED]);
            }
        }
        
        // 有子订单取消，父订单可能取消
        if ($subStatuses->contains(OrderStatus::CANCELLED)) {
            $allCancelled = $parentOrder->subOrders->every(fn($o) => $o->status === OrderStatus::CANCELLED);
            if ($allCancelled) {
                $parentOrder->update(['status' => OrderStatus::CANCELLED]);
            }
        }
    }
}
```

### 8.6 分销与佣金体系

```
分销关系树（三级分销限制）

                    ┌──────────┐
                    │  平台      │
                    │  (抽佣 5%) │
                    └────┬─────┘
                         │
              ┌──────────┼──────────┐
              │          │          │
         ┌────┴────┐ ┌──┴────┐ ┌──┴────┐
         │  A 一级  │ │ B 一级 │ │ C 一级 │  佣金比例：15%
         │ 分销商   │ │ 分销商 │ │ 分销商 │
         └────┬────┘ └──┬────┘ └──┬────┘
              │         │         │
         ┌────┴────┐ ┌──┴────┐
         │ D 二级  │ │ E 二级 │              佣金比例：10%
         │(A的下级) │ │(B的下级)│
         └────┬────┘ └───────┘
              │
         ┌────┴────┐
         │ F 三级  │                            佣金比例：5%
         │(D的下级)│
         └─────────┘

佣金结算规则：
- F 下单购买，F 无佣金（不能自购）
- D 获得二级佣金 10%
- A 获得一级佣金 15%
- B 无佣金（无关联）
- 平台获得 5% 服务费
```

```php
class CommissionService
{
    /**
     * 计算订单佣金
     */
    public function calculate(Order $order): array
    {
        $buyer = $order->user;
        $commissions = [];
        
        // 查找分销商链路（最多3级）
        $distributors = $this->findDistributorChain($buyer->inviter_id, 3);
        
        $levels = [
            1 => 0.15, // 一级 15%
            2 => 0.10, // 二级 10%
            3 => 0.05, // 三级 5%
        ];
        
        foreach ($distributors as $index => $distributor) {
            $level = $index + 1;
            $rate = $levels[$level] ?? 0;
            $amount = $order->payable_amount * $rate;
            
            $commissions[] = [
                'distributor_id' => $distributor->id,
                'level' => $level,
                'rate' => $rate,
                'amount' => $amount,
                'order_id' => $order->id,
                'status' => CommissionStatus::PENDING, // 冻结中，待结算
            ];
        }
        
        return $commissions;
    }
    
    /**
     * 佣金结算（订单完成后 T+7 天）
     */
    public function settle(Commission $commission): void
    {
        DB::transaction(function () use ($commission) {
            // 1. 更新佣金状态
            $commission->update([
                'status' => CommissionStatus::SETTLED,
                'settled_at' => now(),
            ]);
            
            // 2. 增加分销商钱包余额
            $commission->distributor->wallet->increment('balance', $commission->amount);
            
            // 3. 记录钱包流水
            WalletTransaction::create([
                'user_id' => $commission->distributor_id,
                'amount' => $commission->amount,
                'type' => 'COMMISSION',
                'reference_id' => $commission->id,
                'reference_type' => Commission::class,
                'remark' => "佣金结算 - 订单 #{$commission->order->order_no}",
            ]);
        });
    }
    
    /**
     * 佣金提现审核
     */
    public function withdrawRequest(Distributor $distributor, float $amount): WithdrawRequest
    {
        if ($distributor->wallet->balance < $amount) {
            throw new InsufficientBalanceException('余额不足');
        }
        
        // 冻结金额
        $distributor->wallet->decrement('balance', $amount);
        $distributor->wallet->increment('frozen', $amount);
        
        return WithdrawRequest::create([
            'distributor_id' => $distributor->id,
            'amount' => $amount,
            'status' => WithdrawStatus::PENDING_AUDIT,
            'requested_at' => now(),
        ]);
    }
    
    public function auditWithdraw(WithdrawRequest $request, bool $approved, string $remark = null): void
    {
        DB::transaction(function () use ($request, $approved, $remark) {
            if ($approved) {
                // 通过：解冻并转账
                $request->distributor->wallet->decrement('frozen', $request->amount);
                
                // 调用支付网关转账到用户微信/支付宝
                $this->transferToUser($request->distributor, $request->amount);
                
                $request->update([
                    'status' => WithdrawStatus::COMPLETED,
                    'completed_at' => now(),
                    'remark' => $remark,
                ]);
            } else {
                // 拒绝：解冻退回余额
                $request->distributor->wallet->decrement('frozen', $request->amount);
                $request->distributor->wallet->increment('balance', $request->amount);
                
                $request->update([
                    'status' => WithdrawStatus::REJECTED,
                    'remark' => $remark,
                ]);
            }
        });
    }
}
```

---


---

> **文档结束**  
> 本文档应随产品迭代持续更新。

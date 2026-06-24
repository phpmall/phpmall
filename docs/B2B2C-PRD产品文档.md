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

---


## 8. 前端与多端方案

### 8.1 PC 商城：React 19 + Next.js 16

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

### 8.2 H5 / 小程序：UniApp 3

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

### 8.3 管理后台：React 19 + Ant Design 6 + Vite 8.1

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

---


---

## 10. B2B2C 核心模块架构

### 10.1 多租户（多商户）架构

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

### 10.2 RBAC 权限体系

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

### 10.3 商品体系：SPU-SKU 模型

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

### 10.4 订单状态机

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

### 10.5 拆单逻辑（多商家订单）

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

### 10.6 分销与佣金体系

```
分销关系树（三级分销限制）

                    ┌──────────┐
                    │  平台      │
                    │  (抽佣 10%)│
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
- 平台获得 10% 服务费
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

# B2B2C 商城系统技术架构文档

> **文档版本**：v2.0  
> **编写日期**：2026年6月  
> **适用项目**：PHP B2B2C 多商户电商平台  
> **目标读者**：技术负责人、架构师、后端/前端开发工程师、DevOps 工程师

---

## 目录

1. [项目概述](#1-项目概述)
2. [技术栈总览](#2-技术栈总览)
3. [后端架构详解](#3-后端架构详解)
4. [数据层架构详解](#4-数据层架构详解)
5. [缓存与高性能层](#5-缓存与高性能层)
6. [消息队列与异步处理](#6-消息队列与异步处理)
7. [支付与财务体系](#7-支付与财务体系)
8. [前端与多端方案](#8-前端与多端方案)
9. [基础设施与 DevOps](#9-基础设施与-devops)
10. [B2B2C 核心模块架构](#10-b2b2c-核心模块架构)
11. [版本号修正说明](#11-版本号修正说明)
12. [附录](#12-附录)
13. [深度附录：Monorepo 关键配置详解](#13-深度附录monorepo-关键配置详解)
14. [Monorepo 开发工作流总结](#14-monorepo-开发工作流总结)
15. [多端控制器目录结构设计](#15-多端控制器目录结构设计)

---

## 1. 项目概述

### 1.1 项目背景

本系统为 **B2B2C（Business-to-Business-to-Consumer）** 多商户电商平台，支持以下核心角色：

| 角色 | 说明 |
|------|------|
| **平台运营方** | 系统所有者，负责商户入驻审核、平台营销、规则制定 |
| **商家（B）** | 入驻商户，发布商品、处理订单、管理库存、提现结算 |
| **消费者（C）** | 终端用户，浏览商品、下单支付、售后维权 |
| **分销商** | 推广商品赚取佣金，支持多级分销 |
| **子账号** | 商家的员工账号，权限由商家主账号分配 |

### 1.2 核心能力矩阵

```
┌─────────────────────────────────────────────────────────────────┐
│                        B2B2C 平台能力矩阵                        │
├──────────┬──────────┬──────────┬──────────┬──────────┬──────────┤
│  多商户   │  商品管理  │  订单履约  │  支付分账  │  分销佣金  │  营销促销  │
│  入驻     │  SPU-SKU  │  状态机   │  统一网关  │  级联树   │  优惠券   │
│  审核     │  多规格   │  拆单发货  │  自动对账  │  冻结结算  │  秒杀活动  │
│  分账     │  价格梯度  │  售后退款  │  虚拟钱包  │  提现审核  │  满减折扣  │
└──────────┴──────────┴──────────┴──────────┴──────────┴──────────┘
```

### 1.3 非功能性需求

| 指标 | 目标值 | 说明 |
|------|--------|------|
| 日活用户（DAU） | 100万+ | 峰值并发按 10% 估算 |
| 峰值 QPS | 50,000+ | 商品列表/详情页为主 |
| 订单峰值 | 10,000 TPS | 秒杀场景下 |
| 系统可用性 | 99.95% | 年度停机时间 < 4.38 小时 |
| 数据一致性 | 强一致性 | 订单、支付、库存必须强一致 |
| 搜索延迟 | P99 < 200ms | Elasticsearch 商品检索 |
| 页面加载 | P99 < 1.5s | 首屏加载时间（CDN + 缓存） |

---

## 2. 技术栈总览

### 2.1 分层技术架构图

```
┌────────────────────────────────────────────────────────────────────────────┐
│                              用户接入层                                     │
│  ┌──────────┐  ┌──────────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐ │
│  │  PC 商城  │  │  Mobile 移动端 │  │  商家后台  │  │  管理后台  │  │ 供应商后台 │ │
│  │  Vue 3   │  │  (H5/小程序/App) │  │  Vue 3   │  │  Vue 3   │  │  Vue 3   │ │
│  │  Vite    │  │    UniApp 3    │  │  Vite    │  │  Vite    │  │  Vite    │ │
│  └──────────┘  └──────────────┘  └──────────┘  └──────────┘  └──────────┘ │
├────────────────────────────────────────────────────────────────────────────┤
│                          网关与负载层                               │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐            │
│  │   CDN    │  │  Nginx    │  │  WAF/防爬 │  │ 限流熔断  │            │
│  │ 静态资源  │  │ 反向代理  │  │  安全网关  │  │ 网关层    │            │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘            │
├────────────────────────────────────────────────────────────────────┤
│                          应用服务层                                 │
│  ┌──────────────────────────────────────────────────────────┐      │
│  │              Laravel 13 + Octane (Swoole)                 │      │
│  │  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐ │      │
│  │  │ 商品服务 │  │ 订单服务 │  │ 支付服务 │  │ 用户服务 │  │ 营销服务 │ │      │
│  │  └────────┘ └────────┘ └────────┘ └────────┘ └────────┘ │      │
│  └──────────────────────────────────────────────────────────┘      │
├────────────────────────────────────────────────────────────────────┤
│                          数据与缓存层                               │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐            │
│  │  MySQL 8.4│  │  Redis 8.8│  │ Elasticsearch │  │ MongoDB  │            │
│  │  主从集群  │  │  缓存集群  │  │    搜索引擎    │  │ 文档存储  │            │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘            │
├────────────────────────────────────────────────────────────────────┤
│                          基础设施层                                 │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐            │
│  │  Docker   │  │  Kubernetes│  │  Prometheus│  │  Sentry  │            │
│  │  容器化   │  │  编排调度  │  │  监控告警  │  │ 异常追踪  │            │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘            │
└────────────────────────────────────────────────────────────────────┘
```

### 2.2 技术选型速查表

| 层级 | 技术组件 | 推荐版本 | 用途 |
|------|----------|----------|------|
| **后端框架** | Laravel | 13.x | 主框架（PHP 8.4+） |
| | Laravel Octane | 2.x | Swoole 驱动，协程高性能 |
| | Swoole | 5.x | 底层协程引擎 |
| **数据库** | MySQL | 8.4.x | 主存储 |
| | Elasticsearch | 8.x | 商品搜索 |
| | MongoDB | 7.x | 文档存储（日志、商品详情） |
| **缓存** | Redis | 8.8.x | 缓存、会话、库存、队列 |
| | OPcache | PHP 内置 | 字节码缓存 |
| **前端** | Vue | 3.x | 全部前端页面（PC 商城、管理后台、商家后台、供应商后台） |
| | Pinia | 3.x | 状态管理 |
| | vue-router | 5.x | 前端路由 |
| | UniApp | 3.x | Mobile 移动端（H5/小程序/App） |
| | Vite | 8.x | 构建工具 |
| | TypeScript | ~5.5-6.0 | 类型系统 |
| | Vitest | 4.x | 单元测试 |
| | Playwright | 1.x | E2E 测试 |
| **队列** | Laravel Horizon | 5.x | Redis 队列监控 |
| **容器** | Docker | 24.x | 容器化 |
| | Kubernetes | 1.29+ | 生产编排 |
| **监控** | Prometheus | 2.x | 指标采集 |
| | Grafana | 10.x | 可视化 |
| | Sentry | 最新 | 异常追踪 |
| **日志** | 阿里云 SLS | - | 日志采集与分析 |
| **支付** | 微信支付/支付宝/银联 | SDK 最新版 | 支付网关 |

---

## 3. 后端架构详解

### 3.1 框架选型：Laravel 13 + Octane (Swoole)

#### 3.1.1 为什么选择 Laravel 13？

| 特性 | 说明 |
|------|------|
| **Eloquent ORM** | 强大的 ActiveRecord 模式，支持关联预加载、查询作用域，适合电商复杂查询 |
| **Migration/Seeder** | 数据库版本控制，团队协作必备 |
| **Queue/Job 系统** | 原生支持多种队列驱动，Horizon 提供监控 UI |
| **Event/Listener** | 订单状态变更、支付成功等事件驱动架构 |
| **Policy/Gate** | 细粒度权限控制，适合 B2B2C 多角色场景 |
| **Package 生态** | `spatie/laravel-permission`（RBAC）、`maatwebsite/excel`（导入导出）、`barryvdh/laravel-debugbar` 等 |
| **PHP 8.4+ 特性** | 支持 Enum、Match 表达式、Fiber（Octane 基础） |

#### 3.1.2 Laravel Octane 高性能模式

```php
// config/octane.php
return [
    'server' => 'swoole',
    'listeners' => [
        // 请求生命周期优化
        Laravel\Octane\Events\RequestReceived::class => [
            // 重置数据库连接池
            // 重置缓存状态
        ],
        Laravel\Octane\Events\TaskReceived::class => [
            // 异步任务处理
        ],
        Laravel\Octane\Events\TickReceived::class => [
            // 定时任务（替代 Cron）
        ],
    ],
    'max_execution_time' => 30,
    'workers' => env('OCTANE_WORKERS', auto), // 自动根据 CPU 核心数
    'task_workers' => env('OCTANE_TASK_WORKERS', auto),
];
```

**Octane 带来的性能提升**：

| 模式 | 并发能力 | 内存占用 | 适用场景 |
|------|----------|----------|----------|
| PHP-FPM | 进程隔离，每次请求加载 | 高 | 传统模式，兼容性好 |
| Octane + Swoole | 常驻内存，协程复用 | 低 | 高并发 API、WebSocket |
| RoadRunner | 常驻内存，进程池 | 中 | 替代方案，Go 编写 |

#### 3.1.3 目录结构规范

```
phpmall/
├── app/
│   ├── Console/Commands/          # 定时任务（对账、结算、库存同步）
│   ├── Events/                    # 业务事件（OrderPaid, StockDeducted）
│   ├── Exceptions/                # 自定义异常
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/              # API 接口（V1/V2 版本控制）
│   │   │   ├── Web/              # Web 路由（SEO 页面）
│   │   │   └── Admin/            # 管理后台接口
│   │   ├── Middleware/            # 中间件（鉴权、限流、日志、跨域）
│   │   └── Requests/              # 表单请求验证（强类型验证）
│   ├── Jobs/                      # 队列任务（订单超时、发货通知、结算）
│   ├── Listeners/                 # 事件监听器
│   ├── Models/                    # Eloquent 模型
│   │   ├── Concerns/              # Trait（可复用逻辑：HasPrice, HasStock）
│   ├── Providers/                 # 服务提供者
│   ├── Repositories/              # 仓库层（数据访问抽象）
│   ├── Services/                  # 业务服务层（支付、订单、库存）
│   │   ├── Order/                 # 订单领域服务
│   │   ├── Payment/               # 支付领域服务
│   │   └── Product/               # 商品领域服务
│   └── Support/                   # 工具类（金额计算、雪花 ID）
├── bootstrap/
├── config/                        # 配置文件
├── database/
│   ├── factories/                 # 模型工厂（测试数据）
│   ├── migrations/                # 数据库迁移
│   └── seeders/                   # 数据填充
├── resources/                     # 视图模板（如需 Blade）
├── routes/
│   ├── api.php                    # API 路由
│   ├── web.php                    # Web 路由
│   └── admin.php                  # 后台路由
├── storage/
├── tests/
│   ├── Feature/                   # 功能测试
│   └── Unit/                      # 单元测试
├── artisan
├── composer.json
└── phpunit.xml
```

### 3.2 核心服务层设计

#### 3.2.1 服务层模式（Service Layer）

```php
// app/Services/Order/OrderCreationService.php
namespace App\Services\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Payment\PaymentGateway;
use App\Services\Product\StockService;
use Illuminate\Support\Facades\DB;

class OrderCreationService
{
    public function __construct(
        private StockService $stockService,
        private PaymentGateway $paymentGateway,
    ) {}

    /**
     * 创建订单（事务包裹）
     * 
     * @param int $userId 用户 ID
     * @param array $cartItems 购物车项
     * @param array $address 收货地址
     * @param string $couponCode 优惠券码
     * @return Order
     * @throws InsufficientStockException
     * @throws InvalidCouponException
     */
    public function create(int $userId, array $cartItems, array $address, ?string $couponCode = null): Order
    {
        return DB::transaction(function () use ($userId, $cartItems, $address, $couponCode) {
            // 1. 扣减库存（Redis 预扣 + 数据库确认）
            $this->stockService->deduct($cartItems);
            
            // 2. 计算价格（商品价 + 运费 - 优惠）
            $priceResult = $this->calculatePrice($cartItems, $couponCode);
            
            // 3. 生成订单主表
            $order = Order::create([
                'user_id' => $userId,
                'order_no' => $this->generateOrderNo(),
                'total_amount' => $priceResult->total,
                'discount_amount' => $priceResult->discount,
                'freight_amount' => $priceResult->freight,
                'payable_amount' => $priceResult->payable,
                'status' => OrderStatus::PENDING_PAYMENT,
                'address' => $address,
                'created_at' => now(),
            ]);
            
            // 4. 生成订单子表
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'sku_id' => $item['sku_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                    'merchant_id' => $item['merchant_id'], // B2B2C 多商户标识
                ]);
            }
            
            // 5. 触发订单创建事件
            event(new OrderCreated($order));
            
            // 6. 推送延迟队列（订单超时自动取消）
            OrderTimeoutCancelJob::dispatch($order)->delay(now()->addMinutes(30));
            
            return $order;
        });
    }
}
```

### 3.3 异常处理与错误码规范

```php
// app/Exceptions/ApiException.php
namespace App\Exceptions;

class ApiException extends \Exception
{
    // 错误码规范
    const CODE_MAP = [
        // 系统级 1000-1999
        1000 => '系统错误',
        1001 => '参数错误',
        1002 => '未授权',
        1003 => '禁止访问',
        1004 => '资源不存在',
        1005 => '请求过于频繁',
        
        // 用户级 2000-2999
        2000 => '用户不存在',
        2001 => '密码错误',
        2002 => '账号已禁用',
        2003 => 'token 无效',
        
        // 商品级 3000-3999
        3000 => '商品不存在',
        3001 => '商品已下架',
        3002 => '库存不足',
        3003 => 'SKU 不存在',
        
        // 订单级 4000-4999
        4000 => '订单不存在',
        4001 => '订单状态不允许操作',
        4002 => '库存扣减失败',
        4003 => '优惠券不可用',
        4004 => '收货地址无效',
        
        // 支付级 5000-5999
        5000 => '支付失败',
        5001 => '订单已支付',
        5002 => '支付金额不匹配',
        5003 => '退款失败',
        
        // 商家级 6000-6999
        6000 => '商家不存在',
        6001 => '商家未审核通过',
        6002 => '商家已冻结',
        
        // 分销级 7000-7999
        7000 => '分销员不存在',
        7001 => '佣金计算错误',
        7002 => '提现金额不足',
    ];
}
```

---

## 4. 数据层架构详解

### 4.1 MySQL 主库设计

#### 4.1.1 分库分表策略

**分表策略**：

| 表名 | 数据量预估 | 分表策略 | 分表键 |
|------|-----------|----------|--------|
| `orders` | 10亿+ | 按 `user_id` 取模 128 张 | `user_id` |
| `order_items` | 50亿+ | 同上，关联 orders | `order_id` |
| `payments` | 10亿+ | 按 `user_id` 取模 64 张 | `user_id` |
| `user_wallet_logs` | 100亿+ | 按 `user_id` 取模 256 张 | `user_id` |
| `merchant_settlements` | 亿级 | 按 `merchant_id` 取模 32 张 | `merchant_id` |
| `products` | 千万级 | 不分表，按 `merchant_id` 分库 | `merchant_id` |
| `product_skus` | 亿级 | 按 `product_id` 取模 64 张 | `product_id` |

**分表实现（Laravel 模型）**：

```php
// app/Models/Concerns/HasSharding.php
trait HasSharding
{
    protected int $shardCount = 128;
    
    public function getTable(): string
    {
        $shardKey = $this->getShardKey();
        $shardIndex = $shardKey % $this->shardCount;
        return parent::getTable() . '_' . $shardIndex;
    }
    
    abstract protected function getShardKey(): int;
}

// app/Models/Order.php
class Order extends Model
{
    use HasSharding;
    
    protected function getShardKey(): int
    {
        return $this->user_id;
    }
}
```

#### 4.1.2 读写分离配置

```php
// config/database.php
'mysql' => [
    'read' => [
        'host' => [
            env('DB_READ_HOST_1', '192.168.1.11'),
            env('DB_READ_HOST_2', '192.168.1.12'),
        ],
        'port' => env('DB_READ_PORT', 3306),
        'database' => env('DB_READ_DATABASE', 'phpmall'),
        'username' => env('DB_READ_USERNAME', 'readonly'),
        'password' => env('DB_READ_PASSWORD', ''),
        'sticky' => true, // 写后读粘滞，避免主从延迟
    ],
    'write' => [
        'host' => [
            env('DB_WRITE_HOST', '192.168.1.10'),
        ],
        'port' => env('DB_WRITE_PORT', 3306),
        'database' => env('DB_WRITE_DATABASE', 'phpmall'),
        'username' => env('DB_WRITE_USERNAME', 'writeuser'),
        'password' => env('DB_WRITE_PASSWORD', ''),
    ],
    'driver' => 'mysql',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
],
```

### 4.2 Elasticsearch 搜索架构

#### 4.2.1 索引设计

```json
// 商品索引映射
{
  "mappings": {
    "properties": {
      "product_id": { "type": "keyword" },
      "merchant_id": { "type": "keyword" },
      "category_id": { "type": "keyword" },
      "category_path": { "type": "keyword" },
      "title": { 
        "type": "text", 
        "analyzer": "ik_max_word",
        "search_analyzer": "ik_smart"
      },
      "subtitle": { "type": "text", "analyzer": "ik_max_word" },
      "keywords": { "type": "keyword" },
      "brand_id": { "type": "keyword" },
      "brand_name": { "type": "keyword" },
      "price": { "type": "scaled_float", "scaling_factor": 100 },
      "market_price": { "type": "scaled_float", "scaling_factor": 100 },
      "stock": { "type": "integer" },
      "sales_count": { "type": "integer" },
      "status": { "type": "keyword" },
      "is_recommend": { "type": "boolean" },
      "is_new": { "type": "boolean" },
      "is_hot": { "type": "boolean" },
      "attributes": {
        "type": "nested",
        "properties": {
          "attr_id": { "type": "keyword" },
          "attr_name": { "type": "keyword" },
          "attr_value": { "type": "keyword" }
        }
      },
      "skus": {
        "type": "nested",
        "properties": {
          "sku_id": { "type": "keyword" },
          "sku_code": { "type": "keyword" },
          "price": { "type": "scaled_float", "scaling_factor": 100 },
          "stock": { "type": "integer" },
          "specs": { "type": "object" }
        }
      },
      "created_at": { "type": "date" },
      "updated_at": { "type": "date" }
    }
  }
}
```

#### 4.2.2 搜索服务封装

```php
// app/Services/Search/ProductSearchService.php
class ProductSearchService
{
    public function __construct(private Client $esClient) {}
    
    public function search(array $filters): array
    {
        $must = [];
        $filter = [];
        
        // 关键词搜索
        if (!empty($filters['keyword'])) {
            $must[] = [
                'multi_match' => [
                    'query' => $filters['keyword'],
                    'fields' => ['title^3', 'subtitle^2', 'keywords'],
                ]
            ];
        }
        
        // 分类过滤
        if (!empty($filters['category_id'])) {
            $filter[] = ['term' => ['category_path' => $filters['category_id']]];
        }
        
        // 价格范围
        if (!empty($filters['price_min']) || !empty($filters['price_max'])) {
            $range = ['price' => []];
            if (!empty($filters['price_min'])) $range['price']['gte'] = $filters['price_min'] * 100;
            if (!empty($filters['price_max'])) $range['price']['lte'] = $filters['price_max'] * 100;
            $filter[] = ['range' => $range];
        }
        
        // 属性过滤（nested）
        if (!empty($filters['attributes'])) {
            foreach ($filters['attributes'] as $attrId => $values) {
                $filter[] = [
                    'nested' => [
                        'path' => 'attributes',
                        'query' => [
                            'bool' => [
                                'must' => [
                                    ['term' => ['attributes.attr_id' => $attrId]],
                                    ['terms' => ['attributes.attr_value' => $values]]
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }
        
        // 聚合：品牌、价格区间、属性
        $aggs = [
            'brands' => ['terms' => ['field' => 'brand_id', 'size' => 50]],
            'price_ranges' => [
                'range' => [
                    'field' => 'price',
                    'ranges' => [
                        ['to' => 10000, 'key' => '0-100'],
                        ['from' => 10000, 'to' => 50000, 'key' => '100-500'],
                        ['from' => 50000, 'to' => 100000, 'key' => '500-1000'],
                        ['from' => 100000, 'key' => '1000+'],
                    ]
                ]
            ]
        ];
        
        $params = [
            'index' => 'products',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must,
                        'filter' => $filter,
                    ]
                ],
                'aggs' => $aggs,
                'sort' => $this->buildSort($filters['sort'] ?? 'default'),
                'from' => ($filters['page'] - 1) * $filters['per_page'],
                'size' => $filters['per_page'],
                'highlight' => [
                    'fields' => [
                        'title' => ['pre_tags' => ['<em>'], 'post_tags' => ['</em>']]
                    ]
                ]
            ]
        ];
        
        return $this->esClient->search($params)->asArray();
    }
}
```

### 4.3 MongoDB 文档存储

```php
// MongoDB 存储场景
// 1. 商品详情 HTML（富文本内容，大字段）
// 2. 用户行为日志（埋点数据）
// 3. 商品评价（海量数据，非结构化）
// 4. 操作日志

// 用户行为日志集合设计
{
    "_id": ObjectId,
    "user_id": 12345,
    "session_id": "sess_abc123",
    "event_type": "page_view",  // page_view, click, add_to_cart, purchase
    "page": "/product/12345",
    "referrer": "https://www.google.com",
    "device": {
        "type": "mobile",  // desktop, mobile, tablet
        "os": "iOS",
        "os_version": "17.0",
        "browser": "Safari",
        "screen": "390x844"
    },
    "location": {
        "country": "CN",
        "province": "广东",
        "city": "深圳"
    },
    "metadata": {
        "product_id": 12345,
        "category_id": 100,
        "merchant_id": 50
    },
    "created_at": ISODate("2024-01-15T10:30:00Z")
}
```

---

## 5. 缓存与高性能层

### 5.1 Redis 缓存架构

#### 5.1.1 缓存分层策略

| 层级 | 缓存内容 | TTL | 更新策略 |
|------|----------|-----|----------|
| **L1 - 应用内存** | Octane 全局变量、配置项 | 常驻 | 监听配置变更事件 |
| **L2 - Redis** | 商品基础信息、库存、购物车、Session | 10min-24h | Cache-Aside |
| **L3 - Nginx 缓存** | 商品详情页、CMS 页面 | 1h-24h | 主动刷新/Purge |
| **L4 - CDN** | 静态资源、图片、JS/CSS | 7d-30d | 文件名 Hash |

#### 5.1.2 缓存 Key 命名规范

```
# 格式: namespace:module:entity:id[:sub]
product:info:{product_id}           # 商品基础信息
product:stock:{sku_id}               # SKU 实时库存
product:detail:{product_id}          # 商品详情
user:cart:{user_id}                  # 用户购物车
user:session:{token}                 # 登录会话
merchant:info:{merchant_id}          # 商家信息
order:status:{order_id}              # 订单状态
category:tree                        # 分类树
category:products:{category_id}      # 分类商品列表
config:system                        # 系统配置
```

#### 5.1.3 秒杀库存扣减（Redis + Lua 原子操作）

```lua
-- scripts/deduct_stock.lua
-- KEYS[1]: 库存 key (product:stock:{sku_id})
-- KEYS[2]: 已售 key (product:sold:{sku_id})
-- ARGV[1]: 扣减数量
-- ARGV[2]: 活动 ID（用于库存隔离）

local stockKey = KEYS[1]
local soldKey = KEYS[2]
local deductCount = tonumber(ARGV[1])
local activityId = ARGV[2]

-- 检查库存是否充足
local stock = redis.call('GET', stockKey)
if not stock then
    return {-1, "库存未初始化"}
end

stock = tonumber(stock)
if stock < deductCount then
    return {-2, "库存不足"}
end

-- 原子扣减
redis.call('DECRBY', stockKey, deductCount)
redis.call('INCRBY', soldKey, deductCount)

-- 记录扣减日志（用于后续对账/回滚）
redis.call('HSET', 'stock:deduct:log:' .. activityId, 
    redis.call('INCR', 'stock:deduct:seq'), 
    cjson.encode({count = deductCount, time = redis.call('TIME')[1]})
)

return {1, stock - deductCount}
```

```php
// PHP 调用
class SeckillStockService
{
    public function deduct(int $skuId, int $quantity, string $activityId): array
    {
        $result = $this->redis->eval(
            file_get_contents(base_path('scripts/deduct_stock.lua')),
            [
                "product:stock:{$skuId}",
                "product:sold:{$skuId}",
                $quantity,
                $activityId
            ],
            2 // 前 2 个是 KEYS
        );
        
        if ($result[0] === 1) {
            // 扣减成功，发送异步消息进行数据库同步
            SyncStockJob::dispatch($skuId, $quantity)->onQueue('stock');
            return ['success' => true, 'remain' => $result[1]];
        }
        
        return ['success' => false, 'message' => $result[1]];
    }
}
```

### 5.2 OPcache + JIT 优化

```ini
; php.ini 配置
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=512
opcache.interned_strings_buffer=64
opcache.max_accelerated_files=100000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
opcache.save_comments=1
opcache.optimization_level=0xFFFFFFFF
opcache.jit=tracing
opcache.jit_buffer_size=256M
opcache.jit_hot_func=100
opcache.jit_hot_loop=100
opcache.jit_hot_return=8
```

**JIT 适用场景**：
- 优惠券计算（复杂数学运算）
- 订单金额分摊（多商品多优惠叠加）
- 分销佣金层级计算（递归树遍历）

---

## 6. 消息队列与异步处理

### 6.1 Laravel Queue + Horizon 架构

```
┌─────────────────────────────────────────┐
│           Laravel Horizon                │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐  │
│  │ Dashboard│ │ Metrics │ │ Failed  │  │
│  │ 监控面板  │ │ 指标统计 │ │ 重试管理 │  │
│  └─────────┘ └─────────┘ └─────────┘  │
└─────────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────┐
│           Redis 队列存储                  │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐    │
│  │ default │ │ orders  │ │ payments│    │
│  │ 普通任务 │ │ 订单队列 │ │ 支付队列 │    │
│  └─────────┘ └─────────┘ └─────────┘    │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐    │
│  │  stock  │ │  email  │ │  sms    │    │
│  │ 库存同步 │ │ 邮件通知 │ │ 短信通知 │    │
│  └─────────┘ └─────────┘ └─────────┘    │
└─────────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────┐
│           Worker 进程池                   │
│  ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐       │
│  │ W1  │ │ W2  │ │ W3  │ │ W4  │       │
│  └─────┘ └─────┘ └─────┘ └─────┘       │
│  balance: auto, processes: 10            │
└─────────────────────────────────────────┘
```

### 6.2 核心队列任务设计

```php
// app/Jobs/Order/OrderTimeoutCancelJob.php
class OrderTimeoutCancelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 3;
    public $backoff = [10, 30, 60]; // 重试间隔
    public $timeout = 60;
    public $queue = 'orders';
    
    public function __construct(private Order $order) {}
    
    public function handle(OrderCancellationService $service): void
    {
        // 幂等检查：订单已支付则跳过
        if ($this->order->status !== OrderStatus::PENDING_PAYMENT) {
            return;
        }
        
        $service->cancel($this->order, CancelReason::TIMEOUT);
        
        // 释放库存
        StockReleaseJob::dispatch($this->order)->onQueue('stock');
        
        // 通知用户
        OrderCancelledNotification::dispatch($this->order)->onQueue('notifications');
    }
    
    public function failed(\Throwable $exception): void
    {
        // 记录失败日志，发送告警
        Log::error('订单超时取消失败', [
            'order_id' => $this->order->id,
            'error' => $exception->getMessage(),
        ]);
        
        AlertService::send("订单 #{$this->order->id} 超时取消失败");
    }
}

// app/Jobs/Payment/SyncPaymentStatusJob.php
class SyncPaymentStatusJob implements ShouldQueue
{
    public $queue = 'payments';
    
    public function handle(): void
    {
        // 查询待支付订单，向支付网关同步状态
        $pendingOrders = Order::where('status', OrderStatus::PENDING_PAYMENT)
            ->where('created_at', '<', now()->subMinutes(30))
            ->cursor();
            
        foreach ($pendingOrders as $order) {
            $status = $this->gateway->query($order->payment_no);
            if ($status === 'SUCCESS') {
                event(new PaymentSuccess($order));
            } elseif ($status === 'CLOSED') {
                event(new PaymentClosed($order));
            }
        }
    }
}

// app/Jobs/Merchant/SettlementJob.php（每日结算）
class SettlementJob implements ShouldQueue
{
    public $queue = 'settlements';
    
    public function handle(): void
    {
        $yesterday = now()->subDay();
        
        Merchant::chunkById(100, function ($merchants) use ($yesterday) {
            foreach ($merchants as $merchant) {
                $orders = Order::where('merchant_id', $merchant->id)
                    ->where('status', OrderStatus::COMPLETED)
                    ->whereDate('completed_at', $yesterday)
                    ->get();
                
                $settlementAmount = $orders->sum(function ($order) {
                    return $order->payable_amount - $order->platform_commission;
                });
                
                // 创建结算单
                Settlement::create([
                    'merchant_id' => $merchant->id,
                    'settlement_no' => $this->generateNo(),
                    'period_start' => $yesterday->startOfDay(),
                    'period_end' => $yesterday->endOfDay(),
                    'order_count' => $orders->count(),
                    'total_amount' => $orders->sum('payable_amount'),
                    'commission_amount' => $orders->sum('platform_commission'),
                    'settlement_amount' => $settlementAmount,
                    'status' => SettlementStatus::PENDING,
                ]);
            }
        });
    }
}
```

### 6.3 Horizon 配置

```php
// config/horizon.php
'environments' => [
    'production' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default', 'orders', 'payments', 'stock', 'notifications'],
            'balance' => 'auto',         // 自动均衡负载
            'autoScalingStrategy' => 'time',
            'maxProcesses' => 20,        // 最大进程数
            'minProcesses' => 5,         // 最小进程数
            'tries' => 3,
            'timeout' => 60,
            'memory' => 256,             // 单进程内存限制(MB)
        ],
        'supervisor-slow' => [
            'connection' => 'redis',
            'queue' => ['settlements', 'reports', 'data_sync'],
            'balance' => 'simple',
            'maxProcesses' => 5,
            'minProcesses' => 1,
            'tries' => 1,
            'timeout' => 1800,           // 30分钟，适合大数据量任务
        ],
    ],
],
```

---

## 7. 支付与财务体系

### 7.1 支付网关架构

```
┌─────────────────────────────────────────────────┐
│              统一支付 SDK                        │
│            PaymentGateway                        │
├─────────────────────────────────────────────────┤
│  ┌─────────┐  ┌─────────┐  ┌─────────┐        │
│  │ 微信支付  │  │ 支付宝   │  │ 银联云闪付 │        │
│  │ Adapter │  │ Adapter │  │ Adapter │        │
│  └────┬────┘  └────┬────┘  └────┬────┘        │
│       └──────────────┴──────────────┘            │
│              统一接口：pay() / refund() / query() │
└─────────────────────────────────────────────────┘
```

```php
// app/Services/Payment/PaymentGateway.php
interface PaymentGatewayInterface
{
    public function pay(array $params): PaymentResult;
    public function refund(array $params): RefundResult;
    public function query(string $tradeNo): QueryResult;
    public function notify(Request $request): NotifyResult;
    public function close(string $tradeNo): bool;
}

// 统一支付服务
class PaymentService
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private WalletService $wallet,
    ) {}
    
    /**
     * 创建支付订单
     */
    public function createPayment(Order $order, string $channel): Payment
    {
        $payment = Payment::create([
            'payment_no' => $this->generatePaymentNo(),
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'user_id' => $order->user_id,
            'amount' => $order->payable_amount,
            'channel' => $channel, // wechat/alipay/unionpay
            'status' => PaymentStatus::PENDING,
        ]);
        
        return $payment;
    }
    
    /**
     * 执行支付（调用第三方）
     */
    public function executePayment(Payment $payment, array $extra = []): PaymentResult
    {
        $result = $this->gateway->pay([
            'payment_no' => $payment->payment_no,
            'amount' => $payment->amount,
            'description' => "订单 #{$payment->order_no}",
            'return_url' => $extra['return_url'] ?? null,
            'notify_url' => route('api.payment.notify', ['channel' => $payment->channel]),
            'openid' => $extra['openid'] ?? null, // 微信支付需要
        ]);
        
        if ($result->isSuccess()) {
            $payment->update([
                'third_party_no' => $result->getTradeNo(),
                'prepay_data' => $result->getPrepayData(), // 小程序/APP 调起参数
            ]);
        }
        
        return $result;
    }
    
    /**
     * 支付回调处理
     */
    public function handleNotify(string $channel, Request $request): NotifyResult
    {
        $result = $this->gateway->notify($request);
        
        if ($result->isSuccess()) {
            $payment = Payment::where('payment_no', $result->getPaymentNo())->first();
            
            if ($payment && $payment->status === PaymentStatus::PENDING) {
                DB::transaction(function () use ($payment, $result) {
                    // 1. 更新支付状态
                    $payment->update([
                        'status' => PaymentStatus::SUCCESS,
                        'paid_at' => $result->getPaidAt(),
                        'third_party_no' => $result->getTradeNo(),
                    ]);
                    
                    // 2. 触发订单支付成功事件
                    event(new OrderPaid($payment->order));
                    
                    // 3. 分账（如果平台抽佣）
                    $this->handleProfitSharing($payment);
                    
                    // 4. 佣金结算（分销）
                    CommissionSettlementJob::dispatch($payment->order)->onQueue('commissions');
                });
            }
        }
        
        return $result;
    }
}
```

### 7.2 分账系统（平台-商户资金分离）

```php
// 微信分账示例
class WechatProfitSharingService
{
    /**
     * 订单支付后分账
     * 平台抽佣 5%，剩余 95% 给商户
     */
    public function share(Order $order): void
    {
        $totalAmount = $order->payable_amount; // 单位：分
        $commissionRate = 0.05; // 平台抽佣比例
        $commissionAmount = (int) ($totalAmount * $commissionRate);
        $merchantAmount = $totalAmount - $commissionAmount;
        
        // 调用微信分账 API
        $result = WechatPay::profitSharing([
            'transaction_id' => $order->payment->third_party_no,
            'out_order_no' => $order->order_no,
            'receivers' => [
                [
                    'type' => 'MERCHANT_ID',
                    'account' => config('wechat.merchant_id'), // 平台商户号
                    'amount' => $commissionAmount,
                    'description' => '平台服务费',
                ],
                [
                    'type' => 'MERCHANT_ID',
                    'account' => $order->merchant->wechat_merchant_id,
                    'amount' => $merchantAmount,
                    'description' => '商家货款',
                ]
            ]
        ]);
        
        // 记录分账流水
        ProfitSharingRecord::create([
            'order_id' => $order->id,
            'total_amount' => $totalAmount,
            'commission_amount' => $commissionAmount,
            'merchant_amount' => $merchantAmount,
            'status' => $result->success ? 'SUCCESS' : 'FAILED',
        ]);
    }
}
```

### 7.3 对账系统

```php
// app/Console/Commands/ReconciliationCommand.php
class ReconciliationCommand extends Command
{
    protected $signature = 'reconciliation:run {date?}';
    
    public function handle(): void
    {
        $date = $this->argument('date') ?? now()->subDay()->format('Y-m-d');
        
        // 1. 拉取微信/支付宝/银联账单
        $bills = [
            'wechat' => WechatPay::downloadBill($date),
            'alipay' => Alipay::downloadBill($date),
            'unionpay' => UnionPay::downloadBill($date),
        ];
        
        foreach ($bills as $channel => $bill) {
            $this->processBill($channel, $bill, $date);
        }
    }
    
    private function processBill(string $channel, array $bill, string $date): void
    {
        foreach ($bill as $row) {
            $payment = Payment::where('third_party_no', $row['transaction_id'])->first();
            
            if (!$payment) {
                // 本地无记录，标记为异常
                ReconciliationException::create([
                    'type' => 'MISSING_LOCAL',
                    'channel' => $channel,
                    'third_party_no' => $row['transaction_id'],
                    'amount' => $row['amount'],
                    'date' => $date,
                ]);
                continue;
            }
            
            // 金额核对
            if ($payment->amount != $row['amount']) {
                ReconciliationException::create([
                    'type' => 'AMOUNT_MISMATCH',
                    'payment_id' => $payment->id,
                    'local_amount' => $payment->amount,
                    'channel_amount' => $row['amount'],
                    'date' => $date,
                ]);
            }
            
            // 状态核对
            $expectedStatus = $row['status'] === 'SUCCESS' ? PaymentStatus::SUCCESS : PaymentStatus::FAILED;
            if ($payment->status !== $expectedStatus) {
                ReconciliationException::create([
                    'type' => 'STATUS_MISMATCH',
                    'payment_id' => $payment->id,
                    'local_status' => $payment->status,
                    'channel_status' => $row['status'],
                    'date' => $date,
                ]);
            }
        }
    }
}
```

### 7.4 虚拟钱包体系

```
┌─────────────────────────────────────────────┐
│              用户钱包结构                      │
├─────────────────────────────────────────────┤
│  user_id: 12345                             │
│  ├─ balance: 10000.00 (可用余额)             │
│  ├─ frozen: 500.00 (冻结金额)                │
│  ├─ total_income: 50000.00 (累计收入)        │
│  └─ total_expense: 40000.00 (累计支出)       │
│                                             │
│  交易类型：                                   │
│  ├─ RECHARGE    充值                        │
│  ├─ PAYMENT     支付                        │
│  ├─ REFUND      退款                        │
│  ├─ WITHDRAW    提现                        │
│  ├─ COMMISSION  佣金收入                     │
│  ├─ FREEZE      冻结                        │
│  └─ UNFREEZE    解冻                        │
└─────────────────────────────────────────────┘
```

```php
// app/Services/Wallet/WalletService.php
class WalletService
{
    /**
     * 原子余额操作（数据库乐观锁）
     */
    public function transfer(int $fromUser, int $toUser, int $amount, string $type, array $meta = []): WalletTransaction
    {
        return DB::transaction(function () use ($fromUser, $toUser, $amount, $type, $meta) {
            // 1. 读取并锁定付款方余额
            $fromWallet = Wallet::where('user_id', $fromUser)->lockForUpdate()->first();
            
            if ($fromWallet->balance < $amount) {
                throw new InsufficientBalanceException('余额不足');
            }
            
            // 2. 扣减付款方
            $fromWallet->decrement('balance', $amount);
            
            // 3. 增加收款方
            $toWallet = Wallet::where('user_id', $toUser)->lockForUpdate()->first();
            $toWallet->increment('balance', $amount);
            
            // 4. 记录双方流水
            $transaction = WalletTransaction::create([
                'from_user_id' => $fromUser,
                'to_user_id' => $toUser,
                'amount' => $amount,
                'type' => $type,
                'meta' => json_encode($meta),
            ]);
            
            return $transaction;
        });
    }
}
```

---

## 8. 前端与多端方案

### 8.1 PC 商城：Laravel Blade + Vite + Tailwind CSS

> **说明**：PC 商城采用 Laravel Blade 服务端渲染，路由定义在 `routes/web.php`，视图模板在 `resources/views/`。买家会员中心（`packages/user`）为独立 Vue 3 SPA，专注已登录用户的订单、售后、地址等资产管理。

```
┌─────────────────────────────────────────────┐
│  PC 商城 (Laravel Blade 服务端渲染)           │
├─────────────────────────────────────────────┤
│  ┌─────────────────────────────────────┐   │
│  │  resources/views/                   │   │
│  │  ├─ home.blade.php      首页        │   │
│  │  ├─ category/          分类列表     │   │
│  │  ├─ product/           商品详情     │   │
│  │  ├─ cart/              购物车       │   │
│  │  ├─ checkout/          结算页       │   │
│  │  ├─ order/             订单中心     │   │
│  │  └─ search/            搜索结果     │   │
│  └─────────────────────────────────────┘   │
│  ┌─────────────────────────────────────┐   │
│  │  routes/web.php                     │   │
│  │  Route::get('/', ...)               │   │
│  │  Route::get('/products/{id}', ...)  │   │
│  └─────────────────────────────────────┘   │
└─────────────────────────────────────────────┘
```

**关键配置**：

```js
// vite.config.js — Laravel Blade 商城资源打包
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    tailwindcss(),
  ],
});
```

买家会员中心（`packages/user`）的独立 Vite 配置见其 `packages/user/vite.config.ts`。

### 8.2 Mobile 移动端（`packages/mobile`）：UniApp 3（H5 / 微信小程序 / App）

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

### 8.3 平台管理后台（`packages/admin`）：Vue 3 + TypeScript + Vite

```typescript
// 管理后台路由结构（Vue Router）
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

### 8.4 商家后台（`packages/seller`）：Vue 3 + TypeScript + Vite

面向入驻商家及子账号，提供商品发布/编辑/上下架、订单处理、库存管理、营销工具、数据报表、结算提现等能力。数据权限按 `merchant_id` 隔离，与平台管理后台共享 Vue 3 组件体系。

### 8.5 供应商后台（`packages/supplier`）：Vue 3 + TypeScript + Vite

面向供应链供应商（可选），提供供货商品管理、采购订单、仓库库存、供货发货、对账结算等能力。技术栈与商家后台一致，便于统一维护和部署。

---

## 9. 基础设施与 DevOps

### 9.1 Docker 容器化

```dockerfile
# Dockerfile (PHP 8.4 + Swoole)
FROM php:8.4-cli-alpine

# 安装系统依赖
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    linux-headers \
    $PHPIZE_DEPS

# 安装 PHP 扩展
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        zip \
        gd \
        bcmath \
        opcache \
        mbstring \
        xml

# 安装 Redis 扩展
RUN pecl install redis swoole \
    && docker-php-ext-enable redis swoole

# 安装 Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 复制项目文件
COPY . .

# 安装依赖（生产环境优化）
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 权限设置
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Octane 端口
EXPOSE 8000

# 使用 Octane 启动
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000", "--workers=auto"]
```

```yaml
# docker-compose.yml (开发环境)
version: '3.8'

services:
  app:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www
      - /var/www/vendor
    environment:
      - APP_ENV=local
      - APP_DEBUG=true
    depends_on:
      - mysql
      - redis
      - elasticsearch
      - mongo

  mysql:
    image: mysql:8.4
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: phpmall
      MYSQL_USER: phpmall
      MYSQL_PASSWORD: secret
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql

  redis:
    image: redis:8.8-alpine
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data
    command: redis-server --appendonly yes

  elasticsearch:
    image: elasticsearch:8.11.0
    environment:
      - discovery.type=single-node
      - xpack.security.enabled=false
      - "ES_JAVA_OPTS=-Xms512m -Xmx512m"
    ports:
      - "9200:9200"
    volumes:
      - es_data:/usr/share/elasticsearch/data

  mongo:
    image: mongo:7
    ports:
      - "27017:27017"
    volumes:
      - mongo_data:/data/db

  horizon:
    build: .
    command: php artisan horizon
    depends_on:
      - redis
      - mysql

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app

volumes:
  mysql_data:
  redis_data:
  es_data:
  mongo_data:
```

### 9.2 Kubernetes 生产编排

```yaml
# k8s/app-deployment.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: phpmall-app
  labels:
    app: phpmall
    tier: backend
spec:
  replicas: 3
  strategy:
    type: RollingUpdate
    rollingUpdate:
      maxSurge: 1
      maxUnavailable: 0
  selector:
    matchLabels:
      app: phpmall
      tier: backend
  template:
    metadata:
      labels:
        app: phpmall
        tier: backend
    spec:
      containers:
        - name: app
          image: registry.example.com/phpmall/app:v1.0.0
          ports:
            - containerPort: 8000
          resources:
            requests:
              memory: "512Mi"
              cpu: "500m"
            limits:
              memory: "1Gi"
              cpu: "1000m"
          livenessProbe:
            httpGet:
              path: /health
              port: 8000
            initialDelaySeconds: 10
            periodSeconds: 10
          readinessProbe:
            httpGet:
              path: /health
              port: 8000
            initialDelaySeconds: 5
            periodSeconds: 5
          env:
            - name: APP_ENV
              value: "production"
            - name: DB_HOST
              valueFrom:
                secretKeyRef:
                  name: phpmall-secrets
                  key: db_host
            - name: REDIS_HOST
              value: "redis-service"
            - name: ES_HOST
              value: "elasticsearch-service:9200"
---
apiVersion: v1
kind: Service
metadata:
  name: phpmall-app-service
spec:
  selector:
    app: phpmall
    tier: backend
  ports:
    - protocol: TCP
      port: 80
      targetPort: 8000
  type: ClusterIP
---
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: phpmall-app-hpa
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: phpmall-app
  minReplicas: 3
  maxReplicas: 20
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
    - type: Resource
      resource:
        name: memory
        target:
          type: Utilization
          averageUtilization: 80
```

### 9.3 监控与告警

```yaml
# Prometheus 规则
# prometheus/rules/phpmall.yml
groups:
  - name: phpmall
    rules:
      - alert: HighErrorRate
        expr: rate(http_requests_total{status=~"5.."}[5m]) / rate(http_requests_total[5m]) > 0.05
        for: 5m
        labels:
          severity: critical
        annotations:
          summary: "错误率过高"
          description: "5xx 错误率超过 5%"

      - alert: HighLatency
        expr: histogram_quantile(0.99, rate(http_request_duration_seconds_bucket[5m])) > 2
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "响应延迟过高"
          description: "P99 延迟超过 2 秒"

      - alert: QueueBacklog
        expr: redis_queue_length > 10000
        for: 10m
        labels:
          severity: warning
        annotations:
          summary: "队列堆积"
          description: "Redis 队列堆积超过 10000 条"

      - alert: MySQLReplicationLag
        expr: mysql_slave_lag_seconds > 5
        for: 5m
        labels:
          severity: critical
        annotations:
          summary: "MySQL 主从延迟"
          description: "主从延迟超过 5 秒"
```

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

## 11. 版本号修正说明

> 以下列出原始选型中需要修正的版本号，并说明推荐版本：

| 组件 | 原始选型 | 问题 | 推荐版本 | 说明 |
|------|----------|------|----------|------|
| 组件 | 您提供的版本 | 验证结果 | 当前版本 | 说明 |
|------|------------|---------|----------|------|
| **Laravel** | 13 | ✅ 已发布 | **13.x** | 2026 年 3 月 17 日发布，当前稳定版，PHP 8.4+ |
| **PHP** | 8.4 | ✅ 已发布 | **8.4.21** | 2024 年 11 月 21 日发布，JIT 增强，属性钩子 |
| **Redis** | 8.8 | ✅ 已发布 | **8.8.0** | 2026 年 5 月 25 日发布，当前最新稳定版，查询引擎内置 |
| **Vite** | 8.1 | ✅ 已发布 | **8.1.x** | 2026 年 6 月 4 日发布，Rolldown 统一构建，10-30x 提速 |
| **Vue** | 3.5 | ✅ 已发布 | **3.5.38** | Composition API + `<script setup>`，TypeScript 原生支持 |
| **Pinia** | 3 | ✅ 已发布 | **3.0.4** | Vue 3 官方状态管理库 |
| **MySQL** | 8.4 | ✅ 已发布 | **8.4.10 LTS** | 2024 年 4 月 10 日发布，LTS 版本至 2032 年 |
| **vue-router** | 5 | ✅ 已发布 | **5.1.0** | Vue 3 官方路由库 |
| **Swoole** | - | ✅ 已发布 | **5.x** | 支持 PHP 8.4+，协程完善，Octane 底层驱动 |
| **MongoDB** | 未指定 | - | **7.x** | 当前稳定版 |
| **ES** | 8.x | 合理 | **8.11+** | 确认使用 8.x 系列即可 |
| **UniApp** | 3.x | 合理 | **3.x** | 当前稳定版 |
| **Nginx** | 未指定 | - | **1.25+** | 主线版本，支持 HTTP/3 |
| **Docker** | 未指定 | - | **24.x** | 当前稳定版 |
| **K8s** | 1.29+ | 合理 | **1.29+** | 确认 |

---

## 12. 附录

### 12.1 环境变量模板

```env
# .env.example
APP_NAME=PHPMall
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxxxxx
APP_DEBUG=false
APP_URL=https://www.example.com
APP_TIMEZONE=Asia/Shanghai
APP_LOCALE=zh-CN

# Octane
OCTANE_SERVER=swoole
OCTANE_WORKERS=auto
OCTANE_TASK_WORKERS=auto
OCTANE_MAX_REQUESTS=500

# Database - Write
DB_WRITE_HOST=mysql-master.internal
DB_WRITE_PORT=3306
DB_WRITE_DATABASE=phpmall
DB_WRITE_USERNAME=phpmall_write
DB_WRITE_PASSWORD=secure_password

# Database - Read
DB_READ_HOST_1=mysql-replica-1.internal
DB_READ_HOST_2=mysql-replica-2.internal
DB_READ_PORT=3306
DB_READ_DATABASE=phpmall
DB_READ_USERNAME=phpmall_read
DB_READ_PASSWORD=secure_password

# Redis
REDIS_HOST=redis-cluster.internal
REDIS_PASSWORD=secure_password
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
REDIS_QUEUE_DB=2
REDIS_SESSION_DB=3

# Elasticsearch
ELASTICSEARCH_HOSTS=http://es-node1:9200,http://es-node2:9200,http://es-node3:9200
ELASTICSEARCH_USERNAME=elastic
ELASTICSEARCH_PASSWORD=secure_password

# MongoDB
MONGO_URI=mongodb://mongo1:27017,mongo2:27017/phpmall?replicaSet=rs0
MONGO_USERNAME=phpmall
MONGO_PASSWORD=secure_password

# Payment - WeChat
WECHAT_APP_ID=wx1234567890
WECHAT_APP_SECRET=xxxxxxxx
WECHAT_MCH_ID=1234567890
WECHAT_API_KEY=xxxxxxxx
WECHAT_CERT_PATH=/secrets/wechat/apiclient_cert.pem
WECHAT_KEY_PATH=/secrets/wechat/apiclient_key.pem

# Payment - Alipay
ALIPAY_APP_ID=2024xxxxxxxx
ALIPAY_PRIVATE_KEY=/secrets/alipay/private_key.pem
ALIPAY_PUBLIC_KEY=/secrets/alipay/alipay_public_key.pem
ALIPAY_ENCRYPT_KEY=xxxxxxxx

# Queue
QUEUE_CONNECTION=redis
HORIZON_PREFIX=horizon:

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

# Filesystem
FILESYSTEM_DISK=oss
OSS_ACCESS_KEY_ID=xxxxxxxx
OSS_ACCESS_KEY_SECRET=xxxxxxxx
OSS_BUCKET=phpmall
OSS_ENDPOINT=oss-cn-hangzhou.aliyuncs.com
OSS_CDN_DOMAIN=https://cdn.example.com

# Sentry
SENTRY_LARAVEL_DSN=https://xxxxxxxx@xxxxxxxx.ingest.sentry.io/xxxxxx
SENTRY_TRACES_SAMPLE_RATE=0.1

# Log
LOG_CHANNEL=sls
LOG_SLS_PROJECT=phpmall-logs
LOG_SLS_ENDPOINT=cn-hangzhou.log.aliyuncs.com
LOG_SLS_ACCESS_KEY_ID=xxxxxxxx
LOG_SLS_ACCESS_KEY_SECRET=xxxxxxxx
```

### 12.2 依赖包清单（composer.json）

```json
{
    "require": {
        "php": "^8.4",
        "laravel/framework": "^13.0",
        "laravel/octane": "^2.0",
        "laravel/horizon": "^5.0",
        "laravel/sanctum": "^4.0",
        "spatie/laravel-permission": "^6.0",
        "spatie/laravel-query-builder": "^5.0",
        "elasticsearch/elasticsearch": "^8.0",
        "mongodb/mongodb": "^1.18",
        "predis/predis": "^2.0",
        "webpatser/laravel-uuid": "^4.0",
        "maatwebsite/excel": "^3.1",
        "barryvdh/laravel-snappy": "^1.0",
        "intervention/image": "^3.0",
        "ramsey/uuid": "^4.0",
        "overtrue/laravel-wechat": "^7.0",
        "yansongda/pay": "^3.0",
        "sentry/sentry-laravel": "^4.0"
    },
    "require-dev": {
        "phpunit/phpunit": "^11.0",
        "phpstan/phpstan": "^1.0",
        "laravel/pint": "^1.0",
        "barryvdh/laravel-debugbar": "^3.0",
        "fakerphp/faker": "^1.23",
        "mockery/mockery": "^1.6"
    }
}
```

### 12.3 开发环境快速启动

```bash
# 1. 克隆项目
git clone https://github.com/your-org/phpmall.git
cd phpmall

# 2. 启动容器
docker-compose up -d

# 3. 安装依赖
docker-compose exec app composer install

# 4. 生成密钥
docker-compose exec app php artisan key:generate

# 5. 运行迁移
docker-compose exec app php artisan migrate

# 6. 填充测试数据
docker-compose exec app php artisan db:seed

# 7. 启动 Horizon（队列）
docker-compose exec app php artisan horizon

# 8. 启动 Octane（开发模式）
docker-compose exec app php artisan octane:start --watch

# 访问 http://localhost
```

---

> **文档结束**  
> 本文档应随项目迭代持续更新，建议在每次架构变更或技术升级后同步修订。


---

## 13. 附录：项目实际技术栈速查

> 前文中的代码示例为架构设计参考，实际项目以本节为准。

### 13.1 实际运行环境

| 层级 | 技术 | 实际版本/配置 |
|------|------|---------------|
| 后端框架 | Laravel | 13.x（PHP 8.4+） |
| 运行时加速 | Laravel Octane | Swoole 驱动 |
| PC 商城 | Laravel Blade | 服务端渲染，`resources/views/`，Vite + Tailwind CSS |
| 买家会员中心 | Vue 3 SPA | `packages/user`，Pinia + vue-router，Vite 构建 |
| 平台管理后台 | Vue 3 SPA | `packages/admin`，Pinia + vue-router，Vite 构建 |
| 商家后台 | Vue 3 SPA | `packages/seller`，Pinia + vue-router，Vite 构建 |
| 供应商后台 | Vue 3 SPA | `packages/supplier`，Pinia + vue-router，Vite 构建 |
| 移动端 | UniApp 3 | `packages/mobile`，H5/微信小程序/App，vue-i18n |
| 状态管理 | Pinia | 3.x |
| 前端路由 | vue-router | 5.x |
| 单元测试 | Vitest + PHPUnit | Vitest 4.x + PHPUnit 12.x |
| E2E 测试 | Playwright | 1.x |
| 代码检查 | Pint + PHPStan + ESLint + Oxlint | PHP PSR-12 + TypeScript |
| 包管理 | Composer + pnpm | pnpm workspace Monorepo |
| API 文档 | Scramble / zircote/swagger-php | 代码即文档，OpenAPI 3.1 |

### 13.2 前端 Monorepo 结构

```
packages/
├── admin/          # 平台管理后台（Vue 3 + TS + Vite）
├── seller/         # 商家后台（Vue 3 + TS + Vite）
├── supplier/       # 供应商后台（Vue 3 + TS + Vite）
├── user/           # 买家会员中心（Vue 3 + TS + Vite）
└── mobile/         # 移动端（UniApp 3 + Vue 3）
```

### 13.3 多端控制器目录规范

后端模块控制器按终端分目录：
```
app/Modules/{Domain}/Http/Controllers/
├── Admin/       # 平台管理后台 API
├── Seller/      # 商家后台 API
├── Supplier/    # 供应商后台 API
├── User/        # 买家会员中心 API
├── Portal/      # PC 商城 / 公共 API
├── Shop/        # 店铺前端 API
└── Common/      # 跨端共享 API
```

---

> **文档结束**
> 本文档应随项目迭代持续更新，建议在每次架构变更或技术升级后同步修订。

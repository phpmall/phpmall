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
│  │ React 19 │  │  (H5/小程序/App) │  │ React 19 │  │ React 19 │  │ React 19 │ │
│  │ Next.js  │  │    UniApp 3    │  │ Ant Des. │  │ Ant Des. │  │ Ant Des. │ │
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
| **前端** | React | 19.x | PC 商城、管理后台、商家后台、供应商后台 |
| | Next.js | 16.x | PC 商城 SSR 渲染 |
| | UniApp | 3.x | Mobile 移动端（H5/小程序/App） |
| | Ant Design | 6.x | 管理后台、商家后台、供应商后台 UI |
| | Vite | 8.1.x | 构建工具 |
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

### 8.1 PC 商城（`apps/website`）：React 19 + Next.js 16

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

### 8.2 Mobile 移动端（`apps/mobile`）：UniApp 3（H5 / 微信小程序 / App）

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

### 8.3 平台管理后台（`apps/admin`）：React 19 + Ant Design 6 + Vite 8.1

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

### 8.4 商家后台（`apps/seller`）：React 19 + Ant Design 6 + Vite 8.1

面向入驻商家及子账号，提供商品发布/编辑/上下架、订单处理、库存管理、营销工具、数据报表、结算提现等能力。数据权限按 `merchant_id` 隔离，与平台管理后台共享 Ant Design 组件体系。

### 8.5 供应商后台（`apps/supplier`）：React 19 + Ant Design 6 + Vite 8.1

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
| **React** | 19 | ✅ 已发布 | **19.2.7** | 2024 年 12 月 5 日发布，RSC 稳定，React Compiler 内置 |
| **Next.js** | - | ✅ 已发布 | **16.2.9** | 2025 年 10 月 22 日发布，Turbopack 稳定，React 19 原生支持 |
| **MySQL** | 8.4 | ✅ 已发布 | **8.4.10 LTS** | 2024 年 4 月 10 日发布，LTS 版本至 2032 年 |
| **Ant Design** | - | ✅ 已发布 | **6.4.5** | 2025 年初发布，零运行时 CSS 变量，React 19 兼容 |
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

## 13. 深度附录：Monorepo 关键配置详解

### 13.1 API 类型自动生成脚本（Laravel OpenAPI → TypeScript/Zod）

#### 13.1.1 Laravel 端：使用 zircote/swagger-php 生成 OpenAPI 3.1 规范

**安装依赖**

```bash
composer require zircote/swagger-php
composer require --dev laravel/prompts  # 可选：用于交互式命令
```

**zircote/swagger-php 核心特性**

| 特性 | 说明 |
|------|------|
| PHP 8 Attributes | 使用原生 `#[OA\...]` 属性标记，无需额外包 |
| 自动扫描 | 通过 `Generator` 扫描指定目录自动收集注解 |
| 模型复用 | 通过 `#[OA\Schema]` 在 Eloquent Model 或独立类上定义复用 Schema |
| 验证集成 | 直接读取 `FormRequest` 的 `rules()` 生成参数定义 |

---

**控制器中使用 OpenAPI Attributes**

```php
// apps/backend/app/Http/Controllers/Api/ProductController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'PHPMall B2B2C 多商户电商平台 API',
    title: 'PHPMall API',
)]
#[OA\Server(url: 'https://api.example.com', description: 'Production')]
#[OA\Server(url: 'http://localhost:8000', description: 'Local Development')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
)]
#[OA\Tag(name: 'Products', description: '商品管理')]
class ProductController extends Controller
{
    /**
     * 商品列表
     *
     * 支持分页、分类筛选、关键词搜索、价格区间过滤。
     */
    #[OA\Get(
        path: '/api/v1/products',
        operationId: 'products.index',
        tags: ['Products'],
        summary: '获取商品列表',
        description: '分页获取商品列表，支持多种筛选条件',
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: '页码',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 1)
    )]
    #[OA\Parameter(
        name: 'per_page',
        in: 'query',
        description: '每页数量',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 20, maximum: 100)
    )]
    #[OA\Parameter(
        name: 'category_id',
        in: 'query',
        description: '分类ID',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'keyword',
        in: 'query',
        description: '搜索关键词',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'price_min',
        in: 'query',
        description: '最低价格（分）',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'price_max',
        in: 'query',
        description: '最高价格（分）',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: '成功',
        content: new OA\JsonContent(
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: ProductSchema::class)),
                    new OA\Property(property: 'current_page', type: 'integer'),
                    new OA\Property(property: 'per_page', type: 'integer'),
                    new OA\Property(property: 'total', type: 'integer'),
                    new OA\Property(property: 'last_page', type: 'integer'),
                ]
            )
        )
    )]
    public function index(Request $request)
    {
        $products = Product::filter($request->all())
            ->paginate($request->input('per_page', 20));

        return new ProductCollection($products);
    }

    /**
     * 商品详情
     */
    #[OA\Get(
        path: '/api/v1/products/{product}',
        operationId: 'products.show',
        tags: ['Products'],
        summary: '获取商品详情',
    )]
    #[OA\Parameter(
        name: 'product',
        in: 'path',
        description: '商品ID',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: '成功',
        content: new OA\JsonContent(ref: ProductSchema::class)
    )]
    #[OA\Response(
        response: 404,
        description: '商品不存在',
        content: new OA\JsonContent(
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'message', type: 'string', example: '商品不存在'),
                ]
            )
        )
    )]
    public function show(Product $product)
    {
        return new ProductResource($product->load(['skus', 'attributes', 'merchant']));
    }

    /**
     * 创建商品
     *
     * 商家创建新商品，需要平台审核通过后才能上架。
     */
    #[OA\Post(
        path: '/api/v1/products',
        operationId: 'products.store',
        tags: ['Products'],
        summary: '创建商品',
        security: [['bearerAuth' => []]],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: StoreProductSchema::class)
    )]
    #[OA\Response(
        response: 201,
        description: '创建成功',
        content: new OA\JsonContent(ref: ProductSchema::class)
    )]
    #[OA\Response(
        response: 422,
        description: '参数校验失败',
        content: new OA\JsonContent(
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(
                        property: 'errors',
                        type: 'object',
                        additionalProperties: new OA\Schema(type: 'array', items: new OA\Items(type: 'string'))
                    ),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: '未授权',
        content: new OA\JsonContent(
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                ]
            )
        )
    )]
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return new ProductResource($product);
    }

    /**
     * 更新商品
     */
    #[OA\Put(
        path: '/api/v1/products/{product}',
        operationId: 'products.update',
        tags: ['Products'],
        summary: '更新商品',
        security: [['bearerAuth' => []]],
    )]
    #[OA\Parameter(
        name: 'product',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: StoreProductSchema::class)
    )]
    #[OA\Response(
        response: 200,
        description: '更新成功',
        content: new OA\JsonContent(ref: ProductSchema::class)
    )]
    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return new ProductResource($product);
    }

    /**
     * 删除商品
     */
    #[OA\Delete(
        path: '/api/v1/products/{product}',
        operationId: 'products.destroy',
        tags: ['Products'],
        summary: '删除商品',
        security: [['bearerAuth' => []]],
    )]
    #[OA\Parameter(
        name: 'product',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 204, description: '删除成功')]
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
```

---

**复用 Schema 定义（独立 Schema 类）**

推荐将 Schema 定义在独立类中，供多个控制器复用。

```php
// apps/backend/app/OpenApi/Schemas/ProductSchema.php
namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductSchema',
    title: '商品',
    description: '商品主信息',
    required: ['id', 'title', 'merchant_id', 'status', 'price'],
)]
class ProductSchema
{
    #[OA\Property(description: '商品ID', type: 'integer', example: 1001)]
    public int $id;

    #[OA\Property(description: '商家ID', type: 'integer', example: 200)]
    public int $merchant_id;

    #[OA\Property(description: '商品标题', type: 'string', example: 'iPhone 15 Pro')]
    public string $title;

    #[OA\Property(description: '副标题', type: 'string', example: '钛金属边框，A17 Pro 芯片')]
    public ?string $subtitle;

    #[OA\Property(description: '商品描述（HTML）', type: 'string')]
    public ?string $description;

    #[OA\Property(description: '主图URL数组', type: 'array', items: new OA\Items(type: 'string'))]
    public array $images;

    #[OA\Property(description: '最低售价（分）', type: 'integer', example: 799900)]
    public int $min_price;

    #[OA\Property(description: '最高售价（分）', type: 'integer', example: 899900)]
    public int $max_price;

    #[OA\Property(description: '总库存', type: 'integer', example: 100)]
    public int $total_stock;

    #[OA\Property(description: '累计销量', type: 'integer', example: 5000)]
    public int $sales_count;

    #[OA\Property(description: '状态', type: 'string', enum: ['draft', 'on_sale', 'off_sale'])]
    public string $status;

    #[OA\Property(description: 'SKU列表', type: 'array', items: new OA\Items(ref: ProductSkuSchema::class))]
    public array $skus;

    #[OA\Property(description: '商品属性', type: 'array', items: new OA\Items(ref: ProductAttributeSchema::class))]
    public array $attributes;

    #[OA\Property(description: '创建时间', type: 'string', format: 'date-time')]
    public string $created_at;

    #[OA\Property(description: '更新时间', type: 'string', format: 'date-time')]
    public string $updated_at;
}
```

```php
// apps/backend/app/OpenApi/Schemas/ProductSkuSchema.php
namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductSkuSchema',
    title: '商品SKU',
    description: '商品规格变体',
    required: ['id', 'sku_code', 'price', 'stock'],
)]
class ProductSkuSchema
{
    #[OA\Property(description: 'SKU ID', type: 'integer', example: 100101)]
    public int $id;

    #[OA\Property(description: 'SKU编码', type: 'string', example: 'SKU-100101')]
    public string $sku_code;

    #[OA\Property(description: '规格组合', type: 'object', example: ['颜色' => '黑色', '存储容量' => '128GB'])]
    public array $specs;

    #[OA\Property(description: '售价（分）', type: 'integer', example: 799900)]
    public int $price;

    #[OA\Property(description: '市场价（划线价，分）', type: 'integer', example: 899900, nullable: true)]
    public ?int $market_price;

    #[OA\Property(description: '库存', type: 'integer', example: 100)]
    public int $stock;

    #[OA\Property(description: 'SKU专属图片', type: 'string', nullable: true)]
    public ?string $image;

    #[OA\Property(description: '状态', type: 'string', enum: ['enabled', 'disabled'])]
    public string $status;
}
```

```php
// apps/backend/app/OpenApi/Schemas/StoreProductSchema.php
namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreProductSchema',
    title: '创建商品请求',
    description: '商家创建商品时的请求体',
    required: ['title', 'category_id', 'skus'],
)]
class StoreProductSchema
{
    #[OA\Property(description: '商品标题', type: 'string', maxLength: 200, example: 'iPhone 15 Pro')]
    public string $title;

    #[OA\Property(description: '副标题', type: 'string', maxLength: 500, nullable: true)]
    public ?string $subtitle;

    #[OA\Property(description: '商品描述（富文本/HTML）', type: 'string', nullable: true)]
    public ?string $description;

    #[OA\Property(description: '分类ID', type: 'integer', example: 100)]
    public int $category_id;

    #[OA\Property(description: '品牌ID', type: 'integer', example: 50, nullable: true)]
    public ?int $brand_id;

    #[OA\Property(description: '商品主图URL数组', type: 'array', items: new OA\Items(type: 'string'))]
    public array $images;

    #[OA\Property(description: '商品属性配置', type: 'array', items: new OA\Items(ref: ProductAttributeSchema::class))]
    public array $attributes;

    #[OA\Property(description: 'SKU列表', type: 'array', items: new OA\Items(ref: StoreProductSkuSchema::class))]
    public array $skus;
}

#[OA\Schema(
    schema: 'StoreProductSkuSchema',
    title: '创建SKU请求',
    required: ['sku_code', 'specs', 'price', 'stock'],
)]
class StoreProductSkuSchema
{
    #[OA\Property(description: 'SKU编码', type: 'string', example: 'SKU-100101')]
    public string $sku_code;

    #[OA\Property(description: '规格组合', type: 'object', example: ['颜色' => '黑色', '存储容量' => '128GB'])]
    public array $specs;

    #[OA\Property(description: '售价（分）', type: 'integer', example: 799900)]
    public int $price;

    #[OA\Property(description: '市场价（划线价，分）', type: 'integer', example: 899900, nullable: true)]
    public ?int $market_price;

    #[OA\Property(description: '库存', type: 'integer', example: 100)]
    public int $stock;

    #[OA\Property(description: 'SKU图片', type: 'string', nullable: true)]
    public ?string $image;

    #[OA\Property(description: '条形码', type: 'string', nullable: true)]
    public ?string $barcode;
}
```

---

**Artisan 命令生成 OpenAPI JSON**

```php
// apps/backend/app/Console/Commands/GenerateOpenApiCommand.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateOpenApiCommand extends Command
{
    protected $signature = 'openapi:generate
                            {--output=../packages/api-contract/openapi.json : 输出文件路径}
                            {--format=json : 输出格式（json 或 yaml）}
                            {--src=app : 扫描源目录（相对于 base_path）}';

    protected $description = '基于 zircote/swagger-php 扫描 Attributes 生成 OpenAPI 3.1 规范';

    public function handle(): int
    {
        $srcDir = base_path($this->option('src'));
        $outputPath = $this->resolveOutputPath();

        $this->info("🔍 扫描目录: {$srcDir}");
        $this->info("📁 输出路径: {$outputPath}");

        if (!is_dir($srcDir)) {
            $this->error("扫描目录不存在: {$srcDir}");
            return self::FAILURE;
        }

        try {
            // 使用 OpenApi Generator 扫描 Attributes
            $openapi = Generator::scan([
                $srcDir,
                // 可同时扫描多个目录
                base_path('routes'),
            ], [
                'openapi' => '3.1.0',
                'validate' => true,
                'debug' => $this->option('verbose'),
            ]);

            if ($openapi === null) {
                $this->error('❌ OpenAPI 生成失败：扫描结果为空');
                return self::FAILURE;
            }

            // 序列化输出
            $format = $this->option('format');
            $content = match ($format) {
                'yaml' => $openapi->toYaml(),
                default => $openapi->toJson(),
            };

            // 确保输出目录存在
            $outputDir = dirname($outputPath);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            file_put_contents($outputPath, $content);

            $this->info('✅ OpenAPI 规范生成成功');
            $this->info("   路径: {$outputPath}");
            $this->info("   格式: {$format}");
            $this->info("   路径数: " . count($openapi->paths));
            $this->info("   模型数: " . count($openapi->components->schemas ?? []));

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('❌ 生成失败: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return self::FAILURE;
        }
    }

    private function resolveOutputPath(): string
    {
        $output = $this->option('output');

        // 支持相对路径（相对于 base_path）
        if (!str_starts_with($output, '/') && !str_starts_with($output, '\\')) {
            $output = base_path($output);
        }

        return $output;
    }
}
```

```php
// apps/backend/routes/console.php（或注册到 ServiceProvider）
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// 注册命令（如果未通过自动发现注册）
Artisan::command('openapi:generate', \App\Console\Commands\GenerateOpenApiCommand::class);
```

**composer.json 脚本配置**

```json
// apps/backend/composer.json
{
    "scripts": {
        "generate-api": "php artisan openapi:generate --output=../packages/api-contract/openapi.json",
        "generate-api:yaml": "php artisan openapi:generate --format=yaml --output=../packages/api-contract/openapi.yaml",
        "generate-api:watch": "php artisan openapi:generate --output=../packages/api-contract/openapi.json && echo 'OpenAPI generated at '$(date)"
    }
}
```

---

**在 Model 上直接定义 Schema（可选）**

如果你的模型结构简单，也可以直接在 Model 上定义 Schema，但独立 Schema 类更灵活。

```php
// apps/backend/app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductModel',
    title: '商品模型',
    description: 'Eloquent Product Model',
)]
class Product extends Model
{
    #[OA\Property(description: '商品ID', type: 'integer')]
    protected $primaryKey = 'id';

    protected $fillable = [
        'merchant_id', 'category_id', 'brand_id',
        'title', 'subtitle', 'description',
        'images', 'attributes',
        'min_price', 'max_price',
        'total_stock', 'sales_count', 'status',
    ];

    protected $casts = [
        'images' => 'array',
        'attributes' => 'array',
        'min_price' => 'integer',
        'max_price' => 'integer',
    ];

    public function skus(): HasMany
    {
        return $this->hasMany(ProductSku::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
```

**zircote/swagger-php 核心命令速查**

| 命令 | 作用 |
|------|------|
| `php artisan openapi:generate` | 生成 OpenAPI JSON |
| `php artisan openapi:generate --format=yaml` | 生成 YAML 格式 |
| `php artisan openapi:generate --src=app/Http/Controllers` | 只扫描控制器目录 |
| `php artisan openapi:generate --output=storage/openapi.json` | 指定输出路径 |
| `composer generate-api` | 通过 composer script 执行 |

---

#### 13.1.1.1 FormRequest 集成：从 rules() 方法自动生成 OpenAPI 参数

**核心思想**：将 Laravel `FormRequest` 的 `rules()` 方法自动解析为 OpenAPI 的 `#[OA\RequestBody]` 或 `#[OA\Parameter]`，避免在控制器和 Request 类中重复定义校验规则。

**实现方案：自定义 Attribute + 反射解析**

```php
// apps/backend/app/OpenApi/Attributes/FromRequest.php
namespace App\OpenApi\Attributes;

use OpenApi\Attributes as OA;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;

/**
 * 标记该操作使用 FormRequest 的 rules() 自动生成 OpenAPI Schema
 * 
 * 使用方式：
 * #[FromRequest(StoreProductRequest::class)]
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class FromRequest
{
    public function __construct(
        public string $requestClass,
        public bool $asBody = true,  // true=请求体, false=查询参数
    ) {}

    /**
     * 解析 rules() 为 OpenAPI Schema 属性数组
     */
    public function toOpenApiProperties(): array
    {
        /** @var FormRequest $request */
        $request = new $this->requestClass();
        
        // 调用 rules() 获取规则数组
        $rules = method_exists($request, 'rules') ? $request->rules() : [];
        
        $properties = [];
        foreach ($rules as $field => $ruleString) {
            $properties[] = $this->parseRuleToProperty($field, $ruleString);
        }
        
        return $properties;
    }

    private function parseRuleToProperty(string $field, string|array $rules): OA\Property
    {
        $ruleArray = is_string($rules) ? explode('|', $rules) : $rules;
        
        $type = 'string';
        $required = true;
        $nullable = false;
        $min = null;
        $max = null;
        $enum = null;
        $format = null;
        $pattern = null;
        $example = null;
        
        foreach ($ruleArray as $rule) {
            $ruleName = $this->parseRuleName($rule);
            $ruleParams = $this->parseRuleParams($rule);
            
            match ($ruleName) {
                'nullable' => $nullable = true,
                'sometimes' => $required = false,
                'required' => $required = true,
                'string' => $type = 'string',
                'integer' => $type = 'integer',
                'numeric' => $type = 'number',
                'boolean' => $type = 'boolean',
                'array' => $type = 'array',
                'email' => $format = 'email',
                'uuid' => $format = 'uuid',
                'date' => $format = 'date',
                'date_format' => $format = 'date-time',
                'url' => $format = 'uri',
                'min' => $min = $ruleParams[0] ?? null,
                'max' => $max = $ruleParams[0] ?? null,
                'between' => [$min, $max] = [$ruleParams[0] ?? null, $ruleParams[1] ?? null],
                'in' => $enum = $ruleParams,
                'regex' => $pattern = $ruleParams[0] ?? null,
                'enum' => $enum = $this->resolveEnumValues($ruleParams[0] ?? null),
                default => null,
            };
        }
        
        // 字段名中的点表示嵌套对象（如 address.city）
        $description = $this->generateDescription($field, $ruleArray);
        
        return new OA\Property(
            property: $field,
            description: $description,
            type: $type,
            nullable: $nullable,
            minimum: $type === 'integer' || $type === 'number' ? $min : null,
            maximum: $type === 'integer' || $type === 'number' ? $max : null,
            minLength: $type === 'string' ? $min : null,
            maxLength: $type === 'string' ? $max : null,
            enum: $enum,
            format: $format,
            pattern: $pattern,
            example: $example,
            required: $required,
        );
    }

    private function parseRuleName(string $rule): string
    {
        return explode(':', $rule)[0];
    }

    private function parseRuleParams(string $rule): array
    {
        $parts = explode(':', $rule);
        return count($parts) > 1 ? explode(',', $parts[1]) : [];
    }

    private function resolveEnumValues(?string $enumClass): ?array
    {
        if (!$enumClass || !enum_exists($enumClass)) {
            return null;
        }
        
        $reflection = new ReflectionClass($enumClass);
        if (!$reflection->isEnum()) {
            return null;
        }
        
        // 支持 Backed Enum 和 Unit Enum
        $cases = $reflection->getCases();
        return array_map(fn($case) => $case->getBackingValue() ?? $case->name, $cases);
    }

    private function generateDescription(string $field, array $rules): string
    {
        $desc = [];
        foreach ($rules as $rule) {
            $name = $this->parseRuleName($rule);
            $params = $this->parseRuleParams($rule);
            
            $desc[] = match ($name) {
                'required' => '必填',
                'nullable' => '可为 null',
                'email' => '邮箱格式',
                'unique' => '唯一值',
                'exists' => "存在于表 {$params[0]}",
                default => null,
            };
        }
        
        return implode(', ', array_filter($desc)) ?: $field;
    }
}
```

**自定义 OpenAPI 处理器：读取 #[FromRequest] 并生成 RequestBody**

```php
// apps/backend/app/OpenApi/Processors/FormRequestProcessor.php
namespace App\OpenApi\Processors;

use OpenApi\Annotations\Operation;
use OpenApi\Analysis;
use OpenApi\Generator;
use App\OpenApi\Attributes\FromRequest;
use ReflectionAttribute;
use ReflectionMethod;

/**
 * zircote/swagger-php 自定义处理器
 * 扫描控制器方法上的 #[FromRequest] 属性，自动注入 RequestBody/Parameter
 */
class FormRequestProcessor
{
    public function __invoke(Analysis $analysis): void
    {
        /** @var Operation[] $operations */
        $operations = $analysis->getAnnotationsOfType(Operation::class);
        
        foreach ($operations as $operation) {
            $reflectionMethod = $this->getReflectionMethod($operation);
            if (!$reflectionMethod) {
                continue;
            }
            
            // 查找 #[FromRequest] 属性
            $attributes = $reflectionMethod->getAttributes(FromRequest::class);
            if (empty($attributes)) {
                continue;
            }
            
            /** @var FromRequest $fromRequest */
            $fromRequest = $attributes[0]->newInstance();
            $properties = $fromRequest->toOpenApiProperties();
            
            if ($fromRequest->asBody) {
                // 生成 RequestBody（POST/PUT/PATCH）
                $this->injectRequestBody($operation, $properties);
            } else {
                // 生成 Query Parameters（GET）
                $this->injectQueryParameters($operation, $properties);
            }
        }
    }

    private function getReflectionMethod(Operation $operation): ?ReflectionMethod
    {
        // 从 _context 提取类名和方法名
        $context = $operation->_context ?? null;
        if (!$context) {
            return null;
        }
        
        $className = $context->namespace . '\\' . $context->class;
        $methodName = $context->method;
        
        if (!class_exists($className) || !method_exists($className, $methodName)) {
            return null;
        }
        
        return new ReflectionMethod($className, $methodName);
    }

    private function injectRequestBody(Operation $operation, array $properties): void
    {
        $schema = new \OpenApi\Annotations\Schema([
            'type' => 'object',
            'required' => array_filter($properties, fn($p) => $p->required),
        ]);
        $schema->properties = $properties;
        
        $requestBody = new \OpenApi\Annotations\RequestBody([
            'required' => true,
            'description' => '自动从 FormRequest 生成',
        ]);
        $requestBody->content = [
            new \OpenApi\Annotations\MediaType([
                'mediaType' => 'application/json',
                'schema' => $schema,
            ]),
        ];
        
        $operation->requestBody = $requestBody;
    }

    private function injectQueryParameters(Operation $operation, array $properties): void
    {
        foreach ($properties as $property) {
            $param = new \OpenApi\Annotations\Parameter([
                'name' => $property->property,
                'in' => 'query',
                'description' => $property->description,
                'required' => $property->required ?? false,
            ]);
            $param->schema = $property;
            
            $operation->parameters[] = $param;
        }
    }
}
```

**注册处理器到 Generator**

```php
// apps/backend/app/Console/Commands/GenerateOpenApiCommand.php
// 修改 handle() 方法，注册自定义处理器

public function handle(): int
{
    // ... 前面的代码 ...

    try {
        $openapi = Generator::scan([
            $srcDir,
            base_path('routes'),
        ], [
            'openapi' => '3.1.0',
            'validate' => true,
            'debug' => $this->option('verbose'),
            'processors' => [
                // 注册自定义处理器（在默认处理器之后执行）
                \App\OpenApi\Processors\FormRequestProcessor::class,
                // 其他自定义处理器...
            ],
        ]);

        // ... 后面的代码 ...
    }
}
```

**使用示例：控制器中简化定义**

```php
// apps/backend/app/Http/Controllers/Api/OrderController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\OrderIndexRequest;
use App\Models\Order;
use OpenApi\Attributes as OA;
use App\OpenApi\Attributes\FromRequest;

#[OA\Tag(name: 'Orders', description: '订单管理')]
class OrderController extends Controller
{
    /**
     * 订单列表
     *
     * 参数自动从 OrderIndexRequest::rules() 生成
     */
    #[OA\Get(
        path: '/api/v1/orders',
        operationId: 'orders.index',
        tags: ['Orders'],
        summary: '获取订单列表',
    )]
    #[FromRequest(OrderIndexRequest::class, asBody: false)]  // ← 自动生成查询参数
    #[OA\Response(
        response: 200,
        description: '成功',
        content: new OA\JsonContent(ref: PaginatedResponse::class)
    )]
    public function index(OrderIndexRequest $request)
    {
        $orders = Order::filter($request->validated())
            ->paginate($request->input('per_page', 20));
        
        return new OrderCollection($orders);
    }

    /**
     * 创建订单
     *
     * 请求体自动从 StoreOrderRequest::rules() 生成
     */
    #[OA\Post(
        path: '/api/v1/orders',
        operationId: 'orders.store',
        tags: ['Orders'],
        summary: '创建订单',
        security: [['bearerAuth' => []]],
    )]
    #[FromRequest(StoreOrderRequest::class, asBody: true)]  // ← 自动生成请求体
    #[OA\Response(
        response: 201,
        description: '创建成功',
        content: new OA\JsonContent(ref: OrderSchema::class)
    )]
    #[OA\Response(
        response: 422,
        description: '参数校验失败',
        content: new OA\JsonContent(ref: ValidationErrorSchema::class)
    )]
    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->validated());
        return new OrderResource($order);
    }
}
```

```php
// apps/backend/app/Http/Requests/StoreOrderRequest.php
namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sku_id' => ['required', 'integer', 'exists:product_skus,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'coupon_code' => ['nullable', 'string', 'size:8'],
            'remark' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', new Enum(PaymentMethod::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'address_id.required' => '请选择收货地址',
            'items.required' => '购物车不能为空',
            'items.*.sku_id.exists' => '商品规格不存在',
            'items.*.quantity.max' => '单次购买数量不能超过99件',
            'coupon_code.size' => '优惠券码格式不正确',
        ];
    }
}
```

**自动生成的 OpenAPI 片段（由 FormRequestProcessor 注入）**

```json
{
  "paths": {
    "/api/v1/orders": {
      "post": {
        "requestBody": {
          "required": true,
          "description": "自动从 FormRequest 生成",
          "content": {
            "application/json": {
              "schema": {
                "type": "object",
                "required": ["address_id", "items", "payment_method"],
                "properties": {
                  "address_id": {
                    "type": "integer",
                    "description": "必填, 存在于表 addresses",
                    "minimum": 1,
                    "x-validation": "required|integer|exists:addresses,id"
                  },
                  "items": {
                    "type": "array",
                    "description": "必填, 最小1项",
                    "minItems": 1,
                    "items": {
                      "type": "object",
                      "properties": {
                        "sku_id": { "type": "integer", "minimum": 1 },
                        "quantity": { "type": "integer", "minimum": 1, "maximum": 99 }
                      }
                    }
                  },
                  "coupon_code": {
                    "type": "string",
                    "nullable": true,
                    "maxLength": 8,
                    "minLength": 8
                  },
                  "remark": {
                    "type": "string",
                    "nullable": true,
                    "maxLength": 500
                  },
                  "payment_method": {
                    "type": "string",
                    "enum": ["wechat", "alipay", "unionpay"]
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
```

---

#### 13.1.1.2 枚举类型（Backed Enum）：PHP 8.4 Enum 映射到 OpenAPI Schema

**PHP 8.4 Backed Enum 定义**

```php
// apps/backend/app/Enums/OrderStatus.php
namespace App\Enums;

/**
 * 订单状态枚举
 */
enum OrderStatus: int
{
    case PENDING_PAYMENT = 10;      // 待付款
    case PAID = 20;                 // 已支付
    case PENDING_SHIPMENT = 30;     // 待发货
    case SHIPPED = 40;              // 已发货
    case PENDING_RECEIPT = 50;      // 待收货
    case RECEIVED = 60;             // 已收货
    case COMPLETED = 70;            // 已完成
    case CANCELLED = 80;            // 已取消
    case REFUNDING = 90;            // 退款中
    case REFUNDED = 100;            // 已退款

    /**
     * 获取可读标签
     */
    public function label(): string
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

    /**
     * 获取允许的状态流转
     */
    public function canTransitionTo(self $target): bool
    {
        return match($this) {
            self::PENDING_PAYMENT => in_array($target, [self::PAID, self::CANCELLED]),
            self::PAID => in_array($target, [self::PENDING_SHIPMENT, self::REFUNDING]),
            self::PENDING_SHIPMENT => in_array($target, [self::SHIPPED, self::REFUNDING]),
            self::SHIPPED => in_array($target, [self::PENDING_RECEIPT, self::REFUNDING]),
            self::PENDING_RECEIPT => in_array($target, [self::RECEIVED, self::REFUNDING]),
            self::RECEIVED => in_array($target, [self::COMPLETED, self::REFUNDING]),
            self::COMPLETED => in_array($target, [self::REFUNDING]),
            self::REFUNDING => in_array($target, [self::REFUNDED]),
            default => false,
        };
    }

    /**
     * 获取所有状态列表（用于前端下拉框）
     */
    public static function options(): array
    {
        return array_map(
            fn(self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}
```

```php
// apps/backend/app/Enums/PaymentMethod.php
namespace App\Enums;

enum PaymentMethod: string
{
    case WECHAT = 'wechat';
    case ALIPAY = 'alipay';
    case UNIONPAY = 'unionpay';
    case WALLET = 'wallet';

    public function label(): string
    {
        return match($this) {
            self::WECHAT => '微信支付',
            self::ALIPAY => '支付宝',
            self::UNIONPAY => '银联云闪付',
            self::WALLET => '余额支付',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::WECHAT => 'wechat-pay',
            self::ALIPAY => 'alipay',
            self::UNIONPAY => 'unionpay',
            self::WALLET => 'wallet',
        };
    }
}
```

**方案一：独立 Schema 类中显式引用 Enum（推荐）**

```php
// apps/backend/app/OpenApi/Schemas/OrderSchema.php
namespace App\OpenApi\Schemas;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderSchema',
    title: '订单',
    required: ['id', 'order_no', 'user_id', 'total_amount', 'status', 'payment_method'],
)]
class OrderSchema
{
    #[OA\Property(description: '订单ID', type: 'integer', example: 10001)]
    public int $id;

    #[OA\Property(description: '订单编号', type: 'string', example: 'P202406150001')]
    public string $order_no;

    #[OA\Property(description: '用户ID', type: 'integer', example: 12345)]
    public int $user_id;

    #[OA\Property(description: '订单总金额（分）', type: 'integer', example: 899900)]
    public int $total_amount;

    #[OA\Property(description: '应付金额（分）', type: 'integer', example: 799900)]
    public int $payable_amount;

    #[OA\Property(
        description: '订单状态',
        type: 'integer',
        enum: [10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
        x: ['enumLabels' => ['待付款', '已支付', '待发货', '已发货', '待收货', '已收货', '已完成', '已取消', '退款中', '已退款']]
    )]
    public int $status;

    #[OA\Property(
        description: '支付方式',
        type: 'string',
        enum: ['wechat', 'alipay', 'unionpay', 'wallet'],
        x: ['enumLabels' => ['微信支付', '支付宝', '银联云闪付', '余额支付']]
    )]
    public string $payment_method;

    #[OA\Property(description: '收货地址', ref: OrderAddressSchema::class)]
    public array $address;

    #[OA\Property(description: '订单商品', type: 'array', items: new OA\Items(ref: OrderItemSchema::class))]
    public array $items;

    #[OA\Property(description: '创建时间', type: 'string', format: 'date-time')]
    public string $created_at;
}
```

**方案二：自定义 Enum Attribute 自动生成 Schema（高级）**

```php
// apps/backend/app/OpenApi/Attributes/EnumSchema.php
namespace App\OpenApi\Attributes;

use OpenApi\Attributes as OA;
use ReflectionEnum;

/**
 * 自动将 PHP Backed Enum 转为 OpenAPI Schema 属性
 * 
 * 使用方式：
 * #[EnumSchema(enum: OrderStatus::class, description: '订单状态')]
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class EnumSchema
{
    public function __construct(
        public string $enum,
        public string $description = '',
    ) {}

    /**
     * 转为 OpenApi\Property 实例
     */
    public function toProperty(string $propertyName): OA\Property
    {
        $reflection = new ReflectionEnum($this->enum);
        
        if (!$reflection->isBacked()) {
            throw new \InvalidArgumentException("Enum {$this->enum} 必须是 Backed Enum");
        }
        
        // 获取 backing 类型
        $backingType = $reflection->getBackingType();
        $type = $backingType->getName(); // 'int' 或 'string'
        $openApiType = $type === 'int' ? 'integer' : 'string';
        
        // 获取所有 case 的值
        $values = [];
        $labels = [];
        foreach ($reflection->getCases() as $case) {
            $values[] = $case->getBackingValue();
            // 尝试调用 label() 方法获取可读标签
            $labels[] = method_exists($case, 'label') ? $case->label() : $case->name;
        }
        
        return new OA\Property(
            property: $propertyName,
            description: $this->description,
            type: $openApiType,
            enum: $values,
            x: ['enumLabels' => $labels],
        );
    }
}
```

**自定义 Enum Schema 处理器**

```php
// apps/backend/app/OpenApi/Processors/EnumSchemaProcessor.php
namespace App\OpenApi\Processors;

use OpenApi\Analysis;
use OpenApi\Annotations\Schema;
use App\OpenApi\Attributes\EnumSchema;
use ReflectionClass;
use ReflectionProperty;

class EnumSchemaProcessor
{
    public function __invoke(Analysis $analysis): void
    {
        /** @var Schema[] $schemas */
        $schemas = $analysis->getAnnotationsOfType(Schema::class);
        
        foreach ($schemas as $schema) {
            if (!isset($schema->_context->class)) {
                continue;
            }
            
            $className = $schema->_context->namespace . '\\' . $schema->_context->class;
            if (!class_exists($className)) {
                continue;
            }
            
            $reflection = new ReflectionClass($className);
            
            // 扫描类属性上的 #[EnumSchema]
            foreach ($reflection->getProperties() as $property) {
                $attributes = $property->getAttributes(EnumSchema::class);
                if (empty($attributes)) {
                    continue;
                }
                
                /** @var EnumSchema $enumSchema */
                $enumSchema = $attributes[0]->newInstance();
                $oaProperty = $enumSchema->toProperty($property->getName());
                
                // 替换或添加到 schema 的 properties 中
                $this->replaceProperty($schema, $oaProperty);
            }
        }
    }

    private function replaceProperty(Schema $schema, \OpenApi\Annotations\Property $newProperty): void
    {
        if (!isset($schema->properties)) {
            $schema->properties = [];
        }
        
        // 查找并替换同名属性
        foreach ($schema->properties as $index => $property) {
            if ($property->property === $newProperty->property) {
                $schema->properties[$index] = $newProperty;
                return;
            }
        }
        
        // 未找到则追加
        $schema->properties[] = $newProperty;
    }
}
```

**使用 EnumSchema Attribute 的简化定义**

```php
// apps/backend/app/OpenApi/Schemas/OrderSchema.php（简化版）
namespace App\OpenApi\Schemas;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use OpenApi\Attributes as OA;
use App\OpenApi\Attributes\EnumSchema;

#[OA\Schema(
    schema: 'OrderSchema',
    title: '订单',
)]
class OrderSchema
{
    #[OA\Property(description: '订单ID', type: 'integer')]
    public int $id;

    #[EnumSchema(enum: OrderStatus::class, description: '订单状态')]
    public int $status;

    #[EnumSchema(enum: PaymentMethod::class, description: '支付方式')]
    public string $payment_method;
}
```

**前端生成的 Zod Schema 效果**

```typescript
// packages/api-contract/src/schemas/index.ts（自动生成）

// 订单状态
export const OrderStatusSchema = z.enum([10, 20, 30, 40, 50, 60, 70, 80, 90, 100]);
export type OrderStatus = z.infer<typeof OrderStatusSchema>;

// 支付方式
export const PaymentMethodSchema = z.enum(['wechat', 'alipay', 'unionpay', 'wallet']);
export type PaymentMethod = z.infer<typeof PaymentMethodSchema>;

// 订单
export const OrderSchema = z.object({
  id: z.number().int(),
  status: OrderStatusSchema,
  payment_method: PaymentMethodSchema,
});
```

```typescript
// 前端使用：类型安全 + 下拉框数据
import { OrderStatusSchema, PaymentMethodSchema } from '@phpmall/api-contract';

// 1. 运行时校验
const order = OrderSchema.parse(apiResponse);

// 2. 类型安全的 switch
switch (order.status) {
  case 10: // 待付款
    showPaymentButton();
    break;
  case 70: // 已完成
    showReviewButton();
    break;
}

// 3. 下拉框选项（从 enumLabels 元数据生成）
const statusOptions = [
  { value: 10, label: '待付款' },
  { value: 20, label: '已支付' },
  // ...
];
```

---

#### 13.1.1.3 分页响应通用 Schema：PaginatedResponse 复用封装

**核心设计：一个通用分页结构，所有列表接口复用**

```php
// apps/backend/app/OpenApi/Schemas/PaginatedResponse.php
namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

/**
 * 通用分页响应结构
 * 
 * 使用方式：
 * #[OA\Response(
 *     response: 200,
 *     content: new OA\JsonContent(
 *         allOf: [
 *             new OA\Schema(ref: PaginatedResponse::class),
 *             new OA\Schema(properties: [
 *                 new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: ProductSchema::class))
 *             ])
 *         ]
 *     )
 * )]
 */
#[OA\Schema(
    schema: 'PaginatedResponse',
    title: '分页响应',
    description: '所有分页列表接口的统一外层结构',
)]
class PaginatedResponse
{
    #[OA\Property(
        description: '当前页数据',
        type: 'array',
        items: new OA\Items(type: 'object'),
        // 实际使用时通过 allOf 覆盖具体的 items 类型
    )]
    public array $data;

    #[OA\Property(
        description: '分页元信息',
        type: 'object',
        required: ['current_page', 'per_page', 'total', 'last_page', 'from', 'to'],
        properties: [
            new OA\Property(property: 'current_page', type: 'integer', description: '当前页码', example: 1),
            new OA\Property(property: 'per_page', type: 'integer', description: '每页数量', example: 20),
            new OA\Property(property: 'total', type: 'integer', description: '总记录数', example: 156),
            new OA\Property(property: 'last_page', type: 'integer', description: '总页数', example: 8),
            new OA\Property(property: 'from', type: 'integer', description: '当前页起始序号', example: 1, nullable: true),
            new OA\Property(property: 'to', type: 'integer', description: '当前页结束序号', example: 20, nullable: true),
            new OA\Property(property: 'path', type: 'string', description: '基础路径', example: 'https://api.example.com/api/v1/products'),
            new OA\Property(property: 'first_page_url', type: 'string', description: '首页URL', example: 'https://api.example.com/api/v1/products?page=1'),
            new OA\Property(property: 'last_page_url', type: 'string', description: '末页URL', example: 'https://api.example.com/api/v1/products?page=8'),
            new OA\Property(property: 'next_page_url', type: 'string', description: '下一页URL', nullable: true, example: 'https://api.example.com/api/v1/products?page=2'),
            new OA\Property(property: 'prev_page_url', type: 'string', description: '上一页URL', nullable: true, example: null),
        ]
    )]
    public object $meta;

    #[OA\Property(
        description: '分页链接（Laravel 风格）',
        type: 'object',
        properties: [
            new OA\Property(property: 'first', type: 'string', description: '首页'),
            new OA\Property(property: 'last', type: 'string', description: '末页'),
            new OA\Property(property: 'prev', type: 'string', description: '上一页', nullable: true),
            new OA\Property(property: 'next', type: 'string', description: '下一页', nullable: true),
        ]
    )]
    public object $links;
}
```

**方案一：使用 `allOf` 组合（标准 OpenAPI 3.1）**

```php
// apps/backend/app/OpenApi/Schemas/ProductListResponse.php
namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductListResponse',
    title: '商品列表响应',
)]
class ProductListResponse
{
    // 使用 allOf 继承 PaginatedResponse + 覆盖 data 字段类型
}

// 在控制器中使用：
#[OA\Response(
    response: 200,
    description: '商品列表',
    content: new OA\JsonContent(
        allOf: [
            // 1. 继承分页结构
            new OA\Schema(ref: PaginatedResponse::class),
            // 2. 覆盖 data 字段为具体的 Product 数组
            new OA\Schema(
                properties: [
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        description: '商品列表',
                        items: new OA\Items(ref: ProductSchema::class)
                    ),
                ]
            ),
        ]
    )
)]
```

**方案二：使用泛型风格（更简洁，利用 zircote 的 $ref 覆盖）**

```php
// apps/backend/app/OpenApi/Schemas/PaginatedResponse.php
// 扩展为支持泛型风格的快捷方法

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

/**
 * 分页响应工厂类
 * 为不同数据类型快速生成分页响应 Schema
 */
class PaginatedResponseFactory
{
    /**
     * 生成带具体 data 类型的分页响应
     * 
     * @param string $itemRef 列表项的 Schema ref（如 ProductSchema::class）
     * @param string $responseName 响应 Schema 名称（如 ProductListResponse）
     * @param string $description 描述
     * @return array OpenAPI Schema 属性数组
     */
    public static function create(
        string $itemRef,
        string $responseName,
        string $description = '分页列表响应'
    ): OA\Schema {
        return new OA\Schema(
            schema: $responseName,
            title: $description,
            allOf: [
                new OA\Schema(ref: PaginatedResponse::class),
                new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            description: '数据列表',
                            items: new OA\Items(ref: $itemRef)
                        ),
                    ]
                ),
            ]
        );
    }
}
```

**方案三：使用自定义 Attribute（推荐，最简洁）**

```php
// apps/backend/app/OpenApi/Attributes/Paginated.php
namespace App\OpenApi\Attributes;

use OpenApi\Attributes as OA;

/**
 * 标记响应为分页结构，自动注入分页字段
 * 
 * 使用方式：
 * #[Paginated(itemRef: ProductSchema::class, description: '商品列表')]
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Paginated
{
    public function __construct(
        public string $itemRef,
        public string $description = '分页列表',
    ) {}

    /**
     * 生成完整的 OpenAPI Response 属性
     */
    public function toResponse(): OA\Response
    {
        return new OA\Response(
            response: 200,
            description: $this->description,
            content: new OA\JsonContent(
                allOf: [
                    new OA\Schema(ref: 'PaginatedResponse'),
                    new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(ref: $this->itemRef)
                            ),
                        ]
                    ),
                ]
            )
        );
    }
}
```

**控制器中使用分页响应**

```php
// apps/backend/app/Http/Controllers/Api/ProductController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use OpenApi\Attributes as OA;
use App\OpenApi\Attributes\Paginated;
use App\OpenApi\Attributes\FromRequest;
use App\OpenApi\Schemas\ProductSchema;
use App\Http\Requests\ProductIndexRequest;

#[OA\Tag(name: 'Products', description: '商品管理')]
class ProductController extends Controller
{
    /**
     * 商品列表
     *
     * 返回分页商品列表，支持多种筛选条件。
     */
    #[OA\Get(
        path: '/api/v1/products',
        operationId: 'products.index',
        tags: ['Products'],
        summary: '获取商品列表',
    )]
    #[FromRequest(ProductIndexRequest::class, asBody: false)]
    #[Paginated(itemRef: ProductSchema::class, description: '商品分页列表')]
    public function index(ProductIndexRequest $request)
    {
        $products = Product::filter($request->validated())
            ->with(['skus', 'merchant'])
            ->paginate($request->input('per_page', 20));
        
        return new ProductCollection($products);
    }

    /**
     * 订单列表
     */
    #[OA\Get(
        path: '/api/v1/orders',
        operationId: 'orders.index',
        tags: ['Orders'],
        summary: '获取订单列表',
    )]
    #[FromRequest(OrderIndexRequest::class, asBody: false)]
    #[Paginated(itemRef: OrderSchema::class, description: '订单分页列表')]
    public function index(OrderIndexRequest $request)
    {
        // ...
    }

    /**
     * 商家列表
     */
    #[OA\Get(
        path: '/api/v1/merchants',
        operationId: 'merchants.index',
        tags: ['Merchants'],
        summary: '获取商家列表',
    )]
    #[Paginated(itemRef: MerchantSchema::class, description: '商家分页列表')]
    public function index(Request $request)
    {
        // ...
    }
}
```

**Laravel Resource 统一分页格式**

```php
// apps/backend/app/Http/Resources/Json/PaginatedResourceCollection.php
namespace App\Http\Resources\Json;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

/**
 * 统一分页响应格式，与 OpenAPI PaginatedResponse 结构对齐
 */
class PaginatedResourceCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        /** @var AbstractPaginator $paginated */
        $paginated = $this->resource;
        
        return [
            'data' => $this->collection,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
                'from' => $paginated->firstItem(),
                'to' => $paginated->lastItem(),
                'path' => $paginated->path(),
                'first_page_url' => $paginated->url(1),
                'last_page_url' => $paginated->url($paginated->lastPage()),
                'next_page_url' => $paginated->nextPageUrl(),
                'prev_page_url' => $paginated->previousPageUrl(),
            ],
            'links' => [
                'first' => $paginated->url(1),
                'last' => $paginated->url($paginated->lastPage()),
                'prev' => $paginated->previousPageUrl(),
                'next' => $paginated->nextPageUrl(),
            ],
        ];
    }
}
```

```php
// apps/backend/app/Http/Resources/ProductCollection.php
namespace App\Http\Resources;

use App\Http\Resources\Json\PaginatedResourceCollection;

class ProductCollection extends PaginatedResourceCollection
{
    // 可覆盖默认行为，如追加额外字段
    public function toArray($request): array
    {
        $base = parent::toArray($request);
        
        // 追加聚合数据（如筛选可用选项）
        $base['filters'] = [
            'price_ranges' => [
                ['min' => 0, 'max' => 10000, 'count' => 45],
                ['min' => 10000, 'max' => 50000, 'count' => 128],
                ['min' => 50000, 'max' => 100000, 'count' => 67],
            ],
            'brands' => [
                ['id' => 1, 'name' => 'Apple', 'count' => 23],
                ['id' => 2, 'name' => 'Samsung', 'count' => 31],
            ],
        ];
        
        return $base;
    }
}
```

**前端生成的分页类型（TypeScript）**

```typescript
// packages/api-contract/src/types/api.d.ts（自动生成）

// 分页元信息
export interface components {
  schemas: {
    PaginatedResponse: {
      data: unknown[];
      meta: {
        current_page: number;
        per_page: number;
        total: number;
        last_page: number;
        from: number | null;
        to: number | null;
        path: string;
        first_page_url: string;
        last_page_url: string;
        next_page_url: string | null;
        prev_page_url: string | null;
      };
      links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
      };
    };
    
    ProductListResponse: {
      data: components['schemas']['ProductSchema'][];
      meta: components['schemas']['PaginatedResponse']['meta'];
      links: components['schemas']['PaginatedResponse']['links'];
    };
    
    OrderListResponse: {
      data: components['schemas']['OrderSchema'][];
      meta: components['schemas']['PaginatedResponse']['meta'];
      links: components['schemas']['PaginatedResponse']['links'];
    };
  };
}
```

**前端分页 Hook 封装（复用）**

```typescript
// packages/hooks/src/usePagination.ts
import { useState, useCallback } from 'react';

interface PaginatedResponse<T> {
  data: T[];
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
    next_page_url: string | null;
    prev_page_url: string | null;
  };
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
}

interface UsePaginationOptions<T> {
  fetcher: (page: number, perPage: number) => Promise<PaginatedResponse<T>>;
  initialPerPage?: number;
}

export function usePagination<T>(options: UsePaginationOptions<T>) {
  const { fetcher, initialPerPage = 20 } = options;
  
  const [data, setData] = useState<T[]>([]);
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(initialPerPage);
  const [total, setTotal] = useState(0);
  const [lastPage, setLastPage] = useState(1);
  const [loading, setLoading] = useState(false);
  const [hasNext, setHasNext] = useState(false);
  const [hasPrev, setHasPrev] = useState(false);

  const fetch = useCallback(async (targetPage: number = page) => {
    setLoading(true);
    try {
      const response = await fetcher(targetPage, perPage);
      setData(response.data);
      setPage(response.meta.current_page);
      setTotal(response.meta.total);
      setLastPage(response.meta.last_page);
      setHasNext(!!response.links.next);
      setHasPrev(!!response.links.prev);
    } finally {
      setLoading(false);
    }
  }, [fetcher, page, perPage]);

  const nextPage = useCallback(() => {
    if (hasNext && page < lastPage) {
      fetch(page + 1);
    }
  }, [hasNext, page, lastPage, fetch]);

  const prevPage = useCallback(() => {
    if (hasPrev && page > 1) {
      fetch(page - 1);
    }
  }, [hasPrev, page, fetch]);

  const goToPage = useCallback((target: number) => {
    if (target >= 1 && target <= lastPage) {
      fetch(target);
    }
  }, [lastPage, fetch]);

  const changePerPage = useCallback((newPerPage: number) => {
    setPerPage(newPerPage);
    setPage(1);
    fetch(1);
  }, [fetch]);

  return {
    data,
    page,
    perPage,
    total,
    lastPage,
    loading,
    hasNext,
    hasPrev,
    fetch,
    nextPage,
    prevPage,
    goToPage,
    changePerPage,
  };
}

// 使用示例
// const { data: products, page, nextPage, prevPage, loading } = usePagination({
//   fetcher: (page, perPage) => apiClient.get(`/api/v1/products?page=${page}&per_page=${perPage}`),
// });
```

**三种方案对比总结**

| 方案 | 复杂度 | 灵活性 | 推荐场景 |
|------|--------|--------|----------|
| **allOf 组合** | 低 | 中 | 少量分页接口，快速上手 |
| **PaginatedResponseFactory** | 中 | 高 | 中等规模，需要程序化生成 |
| **#[Paginated] Attribute** | 低 | 高 | **推荐**，声明式、最简洁、最统一 |

所有方案最终生成的 OpenAPI 结构完全一致，前端类型生成不受实现方式影响。

#### 13.1.2 类型生成脚本：OpenAPI → TypeScript + Zod

```bash
# 根目录安装代码生成工具
pnpm add -D @openapi-codegen/cli @openapi-codegen/typescript @openapi-codegen/typescript-generators zod
```

```typescript
// scripts/generate-api-types.ts
#!/usr/bin/env tsx
import { generate, parseOpenAPISpec } from '@openapi-codegen/cli';
import { readFileSync, writeFileSync, mkdirSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const OPENAPI_PATH = resolve(__dirname, '../packages/api-contract/openapi.json');
const OUTPUT_DIR = resolve(__dirname, '../packages/api-contract/src');

async function main() {
  console.log('🚀 开始生成 API 类型...');

  // 1. 读取并校验 OpenAPI 规范
  const specContent = readFileSync(OPENAPI_PATH, 'utf-8');
  const spec = JSON.parse(specContent);

  // 2. 生成 TypeScript 类型
  await generateTypes(spec);

  // 3. 生成 Zod Schema（运行时校验）
  await generateZodSchemas(spec);

  // 4. 生成请求客户端
  await generateClient(spec);

  console.log('✅ API 类型生成完成');
}

async function generateTypes(spec: any): Promise<void> {
  const typesDir = resolve(OUTPUT_DIR, 'types');
  mkdirSync(typesDir, { recursive: true });

  // 使用 openapi-typescript 生成类型
  const { execSync } = await import('child_process');
  
  execSync(
    `npx openapi-typescript ${OPENAPI_PATH} --output ${typesDir}/api.d.ts`,
    { stdio: 'inherit' }
  );

  // 生成路径辅助类型
  const pathsContent = generatePathTypes(spec);
  writeFileSync(resolve(typesDir, 'paths.ts'), pathsContent);
}

async function generateZodSchemas(spec: any): Promise<void> {
  const schemasDir = resolve(OUTPUT_DIR, 'schemas');
  mkdirSync(schemasDir, { recursive: true });

  const schemas = spec.components?.schemas || {};
  let zodContent = `// 自动生成的 Zod Schema，请勿手动修改\n// 生成时间: ${new Date().toISOString()}\n\nimport { z } from 'zod';\n\n`;

  for (const [name, schema] of Object.entries(schemas)) {
    zodContent += `\n// ${schema.description || name}\n`;
    zodContent += `export const ${name}Schema = ${jsonSchemaToZod(schema as any)};\n`;
    zodContent += `export type ${name} = z.infer<typeof ${name}Schema>;\n\n`;
  }

  writeFileSync(resolve(schemasDir, 'index.ts'), zodContent);
}

function jsonSchemaToZod(schema: any): string {
  if (schema.type === 'string') {
    let chain = 'z.string()';
    if (schema.minLength) chain += `.min(${schema.minLength})`;
    if (schema.maxLength) chain += `.max(${schema.maxLength})`;
    if (schema.pattern) chain += `.regex(/${schema.pattern}/)`;
    if (schema.enum) chain += `.enum([${schema.enum.map((e: string) => `'${e}'`).join(', ')}])`;
    if (schema.format === 'email') chain += '.email()';
    if (schema.format === 'uuid') chain += '.uuid()';
    if (schema.format === 'date-time') chain += '.datetime()';
    return schema.nullable ? `${chain}.nullable()` : chain;
  }

  if (schema.type === 'integer' || schema.type === 'number') {
    let chain = schema.type === 'integer' ? 'z.number().int()' : 'z.number()';
    if (schema.minimum !== undefined) chain += `.min(${schema.minimum})`;
    if (schema.maximum !== undefined) chain += `.max(${schema.maximum})`;
    return schema.nullable ? `${chain}.nullable()` : chain;
  }

  if (schema.type === 'boolean') {
    return 'z.boolean()';
  }

  if (schema.type === 'array') {
    const items = jsonSchemaToZod(schema.items);
    return `z.array(${items})`;
  }

  if (schema.type === 'object') {
    let content = 'z.object({\n';
    for (const [key, prop] of Object.entries(schema.properties || {})) {
      const isRequired = schema.required?.includes(key);
      const propSchema = jsonSchemaToZod(prop as any);
      content += `  ${key}: ${propSchema}${isRequired ? '' : '.optional()'},\n`;
    }
    content += '})';
    return content;
  }

  if (schema.$ref) {
    const refName = schema.$ref.replace('#/components/schemas/', '');
    return `${refName}Schema`;
  }

  return 'z.any()';
}

async function generateClient(spec: any): Promise<void> {
  const clientDir = resolve(OUTPUT_DIR, 'client');
  mkdirSync(clientDir, { recursive: true });

  // 生成基于 fetch 的客户端
  const clientContent = `// 自动生成的 API 客户端\nimport { z } from 'zod';\nimport * as schemas from '../schemas';\n\nconst BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000';\n\nexport class ApiClient {
  private token: string | null = null;\n\n  setToken(token: string) {\n    this.token = token;\n  }\n\n  async request<T>(method: string, path: string, body?: unknown): Promise<T> {\n    const url = \`\${BASE_URL}\${path}\`;\n    const headers: Record<string, string> = {\n      'Content-Type': 'application/json',\n      'Accept': 'application/json',\n    };\n\n    if (this.token) {\n      headers['Authorization'] = \`Bearer \${this.token}\`;\n    }\n\n    const response = await fetch(url, {\n      method,\n      headers,\n      body: body ? JSON.stringify(body) : undefined,\n    });\n\n    if (!response.ok) {\n      throw new ApiError(response.status, await response.text());\n    }\n\n    return response.json();\n  }\n\n  get<T>(path: string): Promise<T> { return this.request('GET', path); }\n  post<T>(path: string, body: unknown): Promise<T> { return this.request('POST', path, body); }\n  put<T>(path: string, body: unknown): Promise<T> { return this.request('PUT', path, body); }\n  patch<T>(path: string, body: unknown): Promise<T> { return this.request('PATCH', path, body); }\n  delete<T>(path: string): Promise<T> { return this.request('DELETE', path); }\n}\n\nexport class ApiError extends Error {\n  constructor(public status: number, public body: string) {\n    super(\`API Error \${status}: \${body}\`);\n  }\n}\n\nexport const apiClient = new ApiClient();\n`;

  writeFileSync(resolve(clientDir, 'index.ts'), clientContent);
}

function generatePathTypes(spec: any): string {
  // 生成路径参数和响应类型映射
  let content = `// 自动生成的路径类型映射\n\n`;
  const paths = spec.paths || {};
  
  for (const [path, methods] of Object.entries(paths)) {
    const typedPath = path.replace(/{/g, '${').replace(/}/g, '}');
    for (const [method, operation] of Object.entries(methods as any)) {
      if (operation.operationId) {
        content += `// ${method.toUpperCase()} ${path}\n`;
        content += `export type ${operation.operationId}Path = \`${typedPath}\`;\n\n`;
      }
    }
  }
  
  return content;
}

main().catch(console.error);
```

```json
// package.json 添加脚本
{
  "scripts": {
    "generate-api": "tsx scripts/generate-api-types.ts",
    "generate-api:watch": "chokidar 'apps/backend/storage/openapi.json' -c 'pnpm generate-api'"
  }
}
```

#### 13.1.3 生成的类型使用示例

```typescript
// packages/api-contract/src/index.ts
export * from './types/api.d';
export * from './schemas';
export { apiClient, ApiError } from './client';
```

```typescript
// apps/website/app/product/[id]/page.tsx
'use client';

import { useEffect, useState } from 'react';
import { apiClient, ProductSchema } from '@phpmall/api-contract';
import { z } from 'zod';

// 类型自动推导
export default function ProductPage({ params }: { params: { id: string } }) {
  const [product, setProduct] = useState<z.infer<typeof ProductSchema> | null>(null);

  useEffect(() => {
    apiClient.get(`/api/v1/products/${params.id}`)
      .then(data => {
        // 运行时校验
        const product = ProductSchema.parse(data);
        setProduct(product);
      });
  }, [params.id]);

  if (!product) return <div>Loading...</div>;

  return (
    <div>
      <h1>{product.title}</h1>
      <p>{product.price}</p>
    </div>
  );
}
```

```typescript
// apps/admin/src/pages/products/create.tsx
import { apiClient, StoreProductRequestSchema } from '@phpmall/api-contract';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';

// 表单类型直接从后端 schema 继承
type CreateProductForm = z.infer<typeof StoreProductRequestSchema>;

export default function CreateProduct() {
  const { register, handleSubmit, formState: { errors } } = useForm<CreateProductForm>({
    resolver: zodResolver(StoreProductRequestSchema),
  });

  const onSubmit = async (data: CreateProductForm) => {
    await apiClient.post('/api/v1/products', data);
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <input {...register('title')} />
      {errors.title && <span>{errors.title.message}</span>}
      
      <input type="number" {...register('price')} />
      {errors.price && <span>{errors.price.message}</span>}
      
      <button type="submit">创建</button>
    </form>
  );
}
```

---

### 13.2 Turborepo 远程缓存配置

#### 13.2.1 方案对比

| 方案 | 成本 | 适用场景 | 配置复杂度 |
|------|------|----------|-----------|
| **Vercel Remote Cache** | 免费（团队≤1人）/ 付费 | 中小型团队，快速上手 | 低 |
| **自托管 MinIO + Turbo** | 服务器成本 | 大型团队，数据敏感 | 高 |
| **AWS S3 + Turbo** | 按量计费 | 已有 AWS 基础设施 | 中 |
| **阿里云 OSS + Turbo** | 按量计费 | 国内部署，合规要求 | 中 |

#### 13.2.2 Vercel Remote Cache（推荐快速方案）

```bash
# 1. 安装 Vercel CLI
npm i -g vercel

# 2. 登录 Vercel（团队管理员执行）
vercel login

# 3. 链接项目（根目录执行）
vercel link

# 4. 生成 Token（用于 CI）
vercel tokens create
# 保存生成的 token，如: turbo_xxxxxxxxxx
```

**环境变量配置**

```bash
# 本地开发：根目录 .env.local
TURBO_TOKEN=turbo_xxxxxxxxxx
TURBO_TEAM=your-team-name
TURBO_API=https://api.vercel.com

# 团队成员各自执行
vercel env add TURBO_TOKEN
vercel env add TURBO_TEAM
```

```yaml
# CI/CD（GitHub Actions）
name: Build with Remote Cache

on: [push, pull_request]

jobs:
  build:
    runs-on: ubuntu-latest
    env:
      TURBO_TOKEN: ${{ secrets.TURBO_TOKEN }}
      TURBO_TEAM: ${{ vars.TURBO_TEAM }}
    steps:
      - uses: actions/checkout@v4
      - uses: pnpm/action-setup@v3
        with:
          version: 9
      - uses: actions/setup-node@v4
        with:
          node-version: 20
          cache: 'pnpm'
      
      - run: pnpm install
      - run: pnpm turbo run build --remote-only
```

**turbo.json 远程缓存配置**

```json
{
  "$schema": "https://turbo.build/schema.json",
  "globalDependencies": [".env", ".env.local"],
  "globalEnv": [
    "NODE_ENV",
    "API_BASE_URL",
    "TURBO_TOKEN",
    "TURBO_TEAM"
  ],
  "remoteCache": {
    "enabled": true,
    "signature": true
  },
  "tasks": {
    "build": {
      "dependsOn": ["^build"],
      "outputs": [".next/**", "!.next/cache/**", "dist/**", "build/**"],
      "env": ["NODE_ENV", "API_BASE_URL"]
    },
    "dev": {
      "cache": false,
      "persistent": true
    },
    "lint": {
      "dependsOn": ["^build"]
    },
    "test": {
      "dependsOn": ["^build"],
      "outputs": ["coverage/**"]
    },
    "generate-api": {
      "cache": true,
      "inputs": ["apps/backend/storage/openapi.json"],
      "outputs": ["packages/api-contract/src/**"]
    }
  }
}
```

#### 13.2.3 自托管 MinIO 远程缓存（企业方案）

```yaml
# docker/minio/docker-compose.yml
version: '3.8'

services:
  minio:
    image: minio/minio:latest
    command: server /data --console-address ":9001"
    ports:
      - "9000:9000"
      - "9001:9001"
    environment:
      MINIO_ROOT_USER: turborepo
      MINIO_ROOT_PASSWORD: strong-password-123456
    volumes:
      - minio_data:/data

  # 初始化 bucket 的脚本
  mc:
    image: minio/mc:latest
    depends_on:
      - minio
    entrypoint: >
      /bin/sh -c "
        sleep 10;
        mc alias set local http://minio:9000 turborepo strong-password-123456;
        mc mb local/turborepo-cache;
        mc anonymous set none local/turborepo-cache;
      "

volumes:
  minio_data:
```

**Turborepo 配置对接自托管 MinIO**

```bash
# 1. 安装自定义远程缓存适配器
pnpm add -D turbo-remote-cache-minio
```

```javascript
// scripts/turbo-remote-cache.js
const { createServer } = require('turbo-remote-cache-minio');

const server = createServer({
  endpoint: 'http://localhost:9000',
  accessKey: 'turborepo',
  secretKey: 'strong-password-123456',
  bucket: 'turborepo-cache',
  region: 'us-east-1',
  useSSL: false,
  signature: true,
});

server.listen(8080, () => {
  console.log('Turbo remote cache server running on http://localhost:8080');
});
```

```bash
# 2. 启动缓存服务器
node scripts/turbo-remote-cache.js

# 3. 配置环境变量（项目 .env）
export TURBO_REMOTE_CACHE_URL=http://localhost:8080
export TURBO_TOKEN=local-cache-token

# 4. 使用远程缓存构建
pnpm turbo run build --remote-only
```

```yaml
# GitHub Actions 使用自托管 MinIO
- name: Build with Self-Hosted Cache
  run: pnpm turbo run build --remote-only
  env:
    TURBO_REMOTE_CACHE_URL: ${{ secrets.MINIO_CACHE_URL }}
    TURBO_TOKEN: ${{ secrets.MINIO_CACHE_TOKEN }}
```

#### 13.2.4 缓存策略优化

```json
// turbo.json - 缓存优化配置
{
  "tasks": {
    "build": {
      "dependsOn": ["^build"],
      "inputs": [
        "$TURBO_DEFAULT$",
        ".env",
        ".env.local",
        "tsconfig.json"
      ],
      "outputs": [
        ".next/**",
        "!.next/cache/**",
        "dist/**",
        "build/**"
      ],
      "outputLogs": "new-only"
    },
    "test": {
      "dependsOn": ["^build"],
      "inputs": [
        "$TURBO_DEFAULT$",
        "jest.config.js",
        "vitest.config.ts"
      ],
      "outputs": ["coverage/**"],
      "env": ["CI", "NODE_ENV"]
    }
  }
}
```

**缓存命中率优化原则**：
1. **依赖精确声明**：`dependsOn` 必须完整声明所有依赖关系
2. **输入声明完整**：`inputs` 包含所有可能影响输出的文件
3. **排除无关文件**：`!.next/cache/**` 避免缓存无意义的构建产物
4. **环境变量敏感**：`env` 声明会导致缓存失效的环境变量

---

### 13.3 各应用 Vite/Next.js 具体配置

#### 13.3.1 `apps/website` - Next.js 16 生产配置

```javascript
// apps/website/next.config.js
/** @type {import('next').NextConfig} */
const nextConfig = {
  // 输出模式：standalone 用于 Docker 部署
  output: 'standalone',

  // 图片优化配置
  images: {
    domains: ['cdn.example.com', 'oss-cn-hangzhou.aliyuncs.com'],
    remotePatterns: [
      {
        protocol: 'https',
        hostname: '**.alicdn.com',
      },
      {
        protocol: 'https',
        hostname: '**.aliyuncs.com',
      },
    ],
    // 使用 sharp 优化（需要安装 sharp 包）
    formats: ['image/webp', 'image/avif'],
    minimumCacheTTL: 60 * 60 * 24 * 7, // 7天
  },

  // 压缩
  compress: true,

  // 重定向规则：API 代理到后端
  async rewrites() {
    return [
      {
        source: '/api/:path*',
        destination: `${process.env.API_BASE_URL || 'http://localhost:8000'}/api/:path*`,
      },
    ];
  },

  // 安全头
  async headers() {
    return [
      {
        source: '/:path*',
        headers: [
          {
            key: 'X-Frame-Options',
            value: 'SAMEORIGIN',
          },
          {
            key: 'X-Content-Type-Options',
            value: 'nosniff',
          },
          {
            key: 'Referrer-Policy',
            value: 'strict-origin-when-cross-origin',
          },
          {
            key: 'Content-Security-Policy',
            value: "default-src 'self'; script-src 'self' 'unsafe-eval' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' https://api.example.com;",
          },
        ],
      },
    ];
  },

  // 实验性功能（Next.js 16 新特性）
  experimental: {
    // 优化 CSS 加载
    optimizeCss: true,
    // 部分预渲染（PPR）
    ppr: true,
    // React Compiler（自动 memoization）
    reactCompiler: true,
  },

  //  webpack 配置（用于别名解析 workspace packages）
  webpack: (config, { isServer }) => {
    // 确保 workspace packages 被正确解析
    config.resolve.symlinks = true;
    
    // 如果需要特定 loader
    if (!isServer) {
      config.resolve.fallback = {
        ...config.resolve.fallback,
        fs: false,
      };
    }
    
    return config;
  },

  // Turbopack 配置（Next.js 16 默认使用 Turbopack 开发）
  turbopack: {
    resolveAlias: {
      // 工作区别名（ Turbopack 需要显式声明）
      '@phpmall/api-contract': './packages/api-contract/src',
      '@phpmall/utils': './packages/utils/src',
      '@phpmall/hooks': './packages/hooks/src',
    },
  },
};

module.exports = nextConfig;
```

```typescript
// apps/website/tsconfig.json
{
  "extends": "@phpmall/tsconfig/nextjs.json",
  "compilerOptions": {
    "baseUrl": ".",
    "paths": {
      "@/*": ["./app/*"],
      "@/components/*": ["./components/*"],
      "@/lib/*": ["./lib/*"],
      "@/styles/*": ["./styles/*"]
    }
  },
  "include": [
    "next-env.d.ts",
    "**/*.ts",
    "**/*.tsx",
    ".next/types/**/*.ts"
  ],
  "exclude": ["node_modules"]
}
```

```typescript
// apps/website/tailwind.config.ts
import type { Config } from 'tailwindcss';

const config: Config = {
  content: [
    './app/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    // 共享 UI 包
    '../../packages/ui-shared/src/**/*.{js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#1677ff',
          50: '#e6f4ff',
          100: '#bae0ff',
          500: '#1677ff',
          600: '#0958d9',
          900: '#002c8c',
        },
        mall: {
          red: '#ff4d4f',
          green: '#52c41a',
          orange: '#fa8c16',
        },
      },
      fontFamily: {
        sans: ['var(--font-inter)', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ],
};

export default config;
```

#### 13.3.2 `apps/admin` - Vite 8.1 + React 19 + Ant Design 6 配置

```typescript
// apps/admin/vite.config.ts
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { resolve } from 'path';

export default defineConfig({
  plugins: [
    react({
      // React 19 使用新的 JSX 转换
      jsxRuntime: 'automatic',
      // React Compiler 支持（需要 babel 配置）
      babel: {
        plugins: [
          ['babel-plugin-react-compiler', {}],
        ],
      },
    }),
  ],

  // 开发服务器配置
  server: {
    port: 3001,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },

  // 构建输出
  build: {
    outDir: 'dist',
    // 代码分割策略
    rollupOptions: {
      output: {
        manualChunks: {
          // 第三方库分离
          'react-vendor': ['react', 'react-dom', 'react-router-dom'],
          'antd-vendor': ['antd', '@ant-design/icons', '@ant-design/pro-components'],
          'chart-vendor': ['echarts', 'recharts'],
          'utils-vendor': ['lodash-es', 'dayjs', 'zod'],
        },
      },
    },
    // 压缩
    minify: 'terser',
    terserOptions: {
      compress: {
        drop_console: true,
        drop_debugger: true,
      },
    },
    // Source map 仅在开发环境生成
    sourcemap: process.env.NODE_ENV !== 'production',
  },

  // 解析配置
  resolve: {
    alias: {
      '@': resolve(__dirname, './src'),
      '@phpmall/api-contract': resolve(__dirname, '../../packages/api-contract/src'),
      '@phpmall/utils': resolve(__dirname, '../../packages/utils/src'),
      '@phpmall/ui-shared': resolve(__dirname, '../../packages/ui-shared/src'),
    },
  },

  // CSS 配置
  css: {
    preprocessorOptions: {
      less: {
        // Ant Design 6 使用 CSS 变量，但仍需要 less 变量覆盖
        modifyVars: {
          'primary-color': '#1677ff',
          'border-radius-base': '6px',
        },
        javascriptEnabled: true,
      },
    },
  },

  // 优化依赖预构建
  optimizeDeps: {
    include: [
      'react',
      'react-dom',
      'antd',
      '@ant-design/icons',
      'react-router-dom',
      'dayjs',
    ],
  },

  // 定义全局变量
  define: {
    __APP_VERSION__: JSON.stringify(process.env.npm_package_version),
    __BUILD_TIME__: JSON.stringify(new Date().toISOString()),
  },
});
```

```typescript
// apps/admin/tsconfig.json
{
  "extends": "@phpmall/tsconfig/vite.json",
  "compilerOptions": {
    "baseUrl": ".",
    "paths": {
      "@/*": ["src/*"],
      "@phpmall/api-contract": ["../../packages/api-contract/src"],
      "@phpmall/utils": ["../../packages/utils/src"],
      "@phpmall/ui-shared": ["../../packages/ui-shared/src"]
    },
    "jsx": "react-jsx",
    "types": ["vite/client", "node"]
  },
  "include": ["src/**/*.ts", "src/**/*.tsx", "vite.config.ts"],
  "exclude": ["node_modules", "dist"]
}
```

```typescript
// apps/admin/src/main.tsx
import React from 'react';
import ReactDOM from 'react-dom/client';
import { ConfigProvider } from 'antd';
import { BrowserRouter } from 'react-router-dom';
import zhCN from 'antd/locale/zh_CN';
import 'dayjs/locale/zh-cn';
import App from './App';

// Ant Design 6 使用 CSS 变量主题系统
ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <BrowserRouter>
      <ConfigProvider
        locale={zhCN}
        theme={{
          // v6 主题配置（使用 CSS 变量）
          token: {
            colorPrimary: '#1677ff',
            borderRadius: 6,
            colorBgContainer: '#ffffff',
          },
          // 暗色模式切换（v6 支持）
          algorithm: (window.matchMedia('(prefers-color-scheme: dark)').matches)
            ? 'dark'
            : undefined,
        }}
      >
        <App />
      </ConfigProvider>
    </BrowserRouter>
  </React.StrictMode>
);
```

```typescript
// apps/admin/src/App.tsx
import { Suspense, lazy } from 'react';
import { Routes, Route } from 'react-router-dom';
import { Spin } from 'antd';

// 懒加载页面
const Dashboard = lazy(() => import('./pages/Dashboard'));
const ProductList = lazy(() => import('./pages/Products/List'));
const ProductEdit = lazy(() => import('./pages/Products/Edit'));
const OrderList = lazy(() => import('./pages/Orders/List'));
const MerchantAudit = lazy(() => import('./pages/Merchants/Audit'));
const SettlementList = lazy(() => import('./pages/Finance/Settlements'));
const UserList = lazy(() => import('./pages/System/Users'));
const RoleList = lazy(() => import('./pages/System/Roles'));

const PageLoading = () => (
  <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100vh' }}>
    <Spin size="large" tip="加载中..." />
  </div>
);

function App() {
  return (
    <Suspense fallback={<PageLoading />}>
      <Routes>
        <Route path="/" element={<Dashboard />} />
        <Route path="/products" element={<ProductList />} />
        <Route path="/products/:id" element={<ProductEdit />} />
        <Route path="/orders" element={<OrderList />} />
        <Route path="/merchants/audit" element={<MerchantAudit />} />
        <Route path="/finance/settlements" element={<SettlementList />} />
        <Route path="/system/users" element={<UserList />} />
        <Route path="/system/roles" element={<RoleList />} />
      </Routes>
    </Suspense>
  );
}

export default App;
```

#### 13.3.3 `apps/mobile` - UniApp 3 + Vite 配置

```typescript
// apps/mobile/vite.config.ts
import { defineConfig } from 'vite';
import uni from '@dcloudio/vite-plugin-uni';
import { resolve } from 'path';

// UniApp 3 使用 Vite 作为构建工具
export default defineConfig({
  plugins: [
    uni(),
  ],

  // 各平台编译配置
  build: {
    // UniApp 多平台构建时指定 target
    target: 'uni-app',
    // 压缩
    minify: 'terser',
    terserOptions: {
      compress: {
        drop_console: true,
      },
    },
  },

  // 解析别名
  resolve: {
    alias: {
      '@': resolve(__dirname, './src'),
      '@phpmall/api-contract': resolve(__dirname, '../../packages/api-contract/src'),
      '@phpmall/utils': resolve(__dirname, '../../packages/utils/src'),
    },
  },

  // 优化依赖
  optimizeDeps: {
    include: ['vue', '@dcloudio/uni-app'],
  },

  // UniApp 特定配置
  define: {
    'process.env.UNI_PLATFORM': JSON.stringify(process.env.UNI_PLATFORM || 'h5'),
  },

  // 条件编译
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@import "@/styles/vars.scss";`,
      },
    },
  },
});
```

```json
// apps/mobile/package.json（UniApp 3 特定配置）
{
  "name": "mobile",
  "version": "1.0.0",
  "private": true,
  "scripts": {
    "dev:h5": "uni --open --platform h5",
    "dev:mp-weixin": "uni --open --platform mp-weixin",
    "dev:app": "uni --open --platform app",
    "build:h5": "uni build --platform h5",
    "build:mp-weixin": "uni build --platform mp-weixin",
    "build:app": "uni build --platform app",
    "lint": "eslint src --ext .vue,.js,.ts",
    "clean": "rm -rf dist"
  },
  "dependencies": {
    "@dcloudio/uni-app": "3.0.0-alpha-4010520240409001",
    "@dcloudio/uni-h5": "3.0.0-alpha-4010520240409001",
    "@dcloudio/uni-mp-weixin": "3.0.0-alpha-4010520240409001",
    "vue": "^3.4.0",
    "vue-i18n": "^9.0.0",
    "pinia": "^2.1.0",
    "@phpmall/api-contract": "workspace:*",
    "@phpmall/utils": "workspace:*"
  },
  "devDependencies": {
    "@dcloudio/vite-plugin-uni": "3.0.0-alpha-4010520240409001",
    "vite": "^8.1.0",
    "typescript": "^5.5.0",
    "@phpmall/tsconfig": "workspace:*"
  }
}
```

```typescript
// apps/mobile/src/utils/request.ts
// UniApp 封装请求（支持多平台）
import { apiClient } from '@phpmall/api-contract';

// 适配 UniApp 的 request 到标准 fetch
class UniAppApiClient {
  private token: string = '';

  setToken(token: string) {
    this.token = token;
    uni.setStorageSync('token', token);
  }

  async request<T>(method: string, url: string, data?: unknown): Promise<T> {
    return new Promise((resolve, reject) => {
      uni.request({
        url: `${import.meta.env.VITE_API_BASE_URL}${url}`,
        method: method.toUpperCase() as any,
        data,
        header: {
          'Content-Type': 'application/json',
          'Authorization': this.token ? `Bearer ${this.token}` : '',
        },
        success: (res) => {
          if (res.statusCode >= 200 && res.statusCode < 300) {
            resolve(res.data as T);
          } else {
            reject(new Error(`HTTP ${res.statusCode}`));
          }
        },
        fail: (err) => reject(err),
      });
    });
  }

  get<T>(url: string) { return this.request<T>('GET', url); }
  post<T>(url: string, data: unknown) { return this.request<T>('POST', url, data); }
  // ...
}

export const uniApiClient = new UniAppApiClient();
```

```vue
<!-- apps/mobile/src/pages/product/detail.vue -->
<template>
  <view class="product-page">
    <image :src="product?.coverImage" mode="aspectFit" class="cover" />
    <text class="title">{{ product?.title }}</text>
    <text class="price">¥{{ product?.price }}</text>
    <button @click="addToCart" type="primary">加入购物车</button>
  </view>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { onLoad } from '@dcloudio/uni-app';
import { ProductSchema } from '@phpmall/api-contract';
import { z } from 'zod';
import { uniApiClient } from '@/utils/request';

const product = ref<z.infer<typeof ProductSchema> | null>(null);

onLoad((options) => {
  const id = options?.id;
  if (id) {
    loadProduct(Number(id));
  }
});

async function loadProduct(id: number) {
  try {
    const data = await uniApiClient.get(`/api/v1/products/${id}`);
    product.value = ProductSchema.parse(data);
  } catch (err) {
    uni.showToast({ title: '加载失败', icon: 'error' });
  }
}

function addToCart() {
  // 调用购物车 API
  uni.showToast({ title: '已加入购物车', icon: 'success' });
}
</script>

<style lang="scss" scoped>
.product-page {
  padding: 20rpx;
  .cover {
    width: 100%;
    height: 750rpx;
    border-radius: 16rpx;
  }
  .title {
    font-size: 32rpx;
    font-weight: bold;
    margin-top: 20rpx;
  }
  .price {
    font-size: 40rpx;
    color: #ff4d4f;
    margin-top: 16rpx;
  }
}
</style>
```

#### 13.3.4 `apps/seller` - 商家后台 Vite + React 19 + Ant Design 6 配置

商家后台与平台管理后台技术栈一致，均使用 `apps/admin` 同构的 Vite + React + Ant Design 方案，应用目录为 `apps/seller`，构建产物通过 `vp build` 输出到 `dist/`。

```typescript
// apps/seller/vite.config.ts
import { defineConfig, lazyPlugins } from "vite-plus";
import react from "@vitejs/plugin-react";

export default defineConfig({
  plugins: lazyPlugins(() => [react()]),
});
```

#### 13.3.5 `apps/supplier` - 供应商后台 Vite + React 19 + Ant Design 6 配置

供应商后台为可选端，应用目录为 `apps/supplier`，技术栈与商家后台保持一致，便于统一组件复用和部署流水线。

```typescript
// apps/supplier/vite.config.ts
import { defineConfig, lazyPlugins } from "vite-plus";
import react from "@vitejs/plugin-react";

export default defineConfig({
  plugins: lazyPlugins(() => [react()]),
});
```

#### 13.3.6 `packages/tsconfig` - 共享 TypeScript 配置

```json
// packages/tsconfig/base.json
{
  "compilerOptions": {
    "target": "ES2022",
    "lib": ["ES2022", "DOM", "DOM.Iterable"],
    "module": "ESNext",
    "moduleResolution": "bundler",
    "resolveJsonModule": true,
    "allowJs": true,
    "checkJs": false,
    "declaration": true,
    "declarationMap": true,
    "sourceMap": true,
    "strict": true,
    "noUnusedLocals": true,
    "noUnusedParameters": true,
    "noFallthroughCasesInSwitch": true,
    "esModuleInterop": true,
    "skipLibCheck": true,
    "forceConsistentCasingInFileNames": true,
    "isolatedModules": true,
    "verbatimModuleSyntax": true
  },
  "exclude": ["node_modules", "dist", ".next"]
}
```

```json
// packages/tsconfig/nextjs.json
{
  "extends": "./base.json",
  "compilerOptions": {
    "jsx": "preserve",
    "incremental": true,
    "plugins": [
      {
        "name": "next"
      }
    ]
  },
  "include": [
    "next-env.d.ts",
    "**/*.ts",
    "**/*.tsx",
    ".next/types/**/*.ts"
  ]
}
```

```json
// packages/tsconfig/vite.json
{
  "extends": "./base.json",
  "compilerOptions": {
    "jsx": "react-jsx",
    "types": ["vite/client", "node"]
  }
}
```

```json
// packages/tsconfig/package.json
{
  "name": "@phpmall/tsconfig",
  "version": "0.0.0",
  "private": true,
  "files": ["base.json", "nextjs.json", "vite.json"]
}
```

#### 13.3.5 `packages/eslint-config` - 共享 ESLint 配置

```javascript
// packages/eslint-config/index.js
const { resolve } = require('node:path');

const project = resolve(process.cwd(), 'tsconfig.json');

module.exports = {
  extends: [
    'eslint:recommended',
    'plugin:@typescript-eslint/recommended',
    'plugin:react-hooks/recommended',
    'prettier',
  ],
  parser: '@typescript-eslint/parser',
  parserOptions: {
    project,
  },
  plugins: ['@typescript-eslint', 'react-refresh'],
  rules: {
    'react-refresh/only-export-components': [
      'warn',
      { allowConstantExport: true },
    ],
    '@typescript-eslint/no-unused-vars': [
      'error',
      { argsIgnorePattern: '^_', varsIgnorePattern: '^_' },
    ],
  },
  settings: {
    'import/resolver': {
      typescript: {
        project,
      },
    },
  },
};
```

```javascript
// packages/eslint-config/next.js
module.exports = {
  extends: [
    './index.js',
    'next/core-web-vitals',
  ],
  rules: {
    '@next/next/no-html-link-for-pages': 'off',
  },
};
```

```javascript
// packages/eslint-config/react-internal.js
module.exports = {
  extends: [
    './index.js',
    'plugin:react/recommended',
  ],
  settings: {
    react: {
      version: 'detect',
    },
  },
};
```

```json
// packages/eslint-config/package.json
{
  "name": "@phpmall/eslint-config",
  "version": "0.0.0",
  "private": true,
  "main": "index.js",
  "dependencies": {
    "@typescript-eslint/eslint-plugin": "^7.0.0",
    "@typescript-eslint/parser": "^7.0.0",
    "eslint-config-next": "^16.0.0",
    "eslint-config-prettier": "^9.0.0",
    "eslint-plugin-react": "^7.34.0",
    "eslint-plugin-react-hooks": "^4.6.0",
    "eslint-plugin-react-refresh": "^0.4.0"
  }
}
```

---

## 14. Monorepo 开发工作流总结

| 场景 | 命令 | 说明 |
|------|------|------|
| 首次安装 | `pnpm install` | 根目录执行，安装所有 workspace 依赖 |
| 启动所有前端应用 | `pnpm dev:all` | 并行启动 admin / seller / supplier / website / mobile(h5) |
| 只启动后端 | `cd apps/backend && php artisan octane:start --watch` | 单独开发 Laravel 接口 |
| 只启动 PC 商城 | `pnpm dev:website` | 单独开发 PC 商城前端 |
| 只启动管理后台 | `pnpm dev:admin` | 单独开发平台管理后台 |
| 只启动商家后台 | `pnpm dev:seller` | 单独开发商家后台 |
| 只启动供应商后台 | `pnpm dev:supplier` | 单独开发供应商后台 |
| 只启动移动端 H5 | `pnpm dev:mobile` | 单独开发移动端 H5 |
| 生成 API 类型 | `pnpm generate-api` | 从 Laravel 生成 TS 类型到 api-contract |
| 构建所有 | `vp run -r build` | 按依赖顺序构建所有前端应用 |
| 代码检查 | `vp check` | 检查所有前端应用的代码 |
| PHP 测试 | `cd apps/backend && php artisan test` | 运行 Laravel 单元测试 |
| 数据库迁移 | `cd apps/backend && php artisan migrate` | 执行 Laravel 迁移 |
| 清理缓存 | `pnpm clean` | 清理所有构建产物和 node_modules |

> **文档结束**  
> 本文档应随项目迭代持续更新，建议在每次架构变更或技术升级后同步修订。


---

## 15. 多端控制器目录结构设计

基于图片中的 7 个目录（Admin、Common、Portal、Seller、Shop、Supplier、User），以下是完整的控制器目录、路由、中间件和权限体系设计。

### 15.1 目录结构定义

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

### 15.2 各目录详细用途

| 目录 | 端 | 鉴权方式 | 路由前缀 | 用途 |
|------|------|---------|---------|------|
| `Admin` | 平台运营 | Sanctum + RBAC | `/api/admin/v1` | 平台管理后台：商家审核、商品审核、订单仲裁、财务管理、系统配置 |
| `Seller` | 商家 | Sanctum + 商家权限 | `/api/seller/v1` | 商家后台：商品管理、订单处理、库存、营销工具、数据报表、结算提现 |
| `Shop` | C端消费者 | 游客/Sanctum | `/api/shop/v1` | 商城前端：商品浏览、搜索、购物车、下单、支付、售后 |
| `User` | 注册用户 | Sanctum | `/api/user/v1` | 个人中心：地址、订单、收藏、足迹、消息、分销、钱包 |
| `Portal` | 全端 | 无鉴权/游客 | `/api/portal/v1` | 公共内容：首页、分类、广告位、CMS文章、帮助中心、搜索引擎 |
| `Common` | 全端 | 无鉴权 | `/api/common/v1` | 纯工具接口：上传、验证码、地区、物流查询、支付回调 |
| `Supplier` | 供应商 | Sanctum + 供应商权限 | `/api/supplier/v1` | 供应链：采购、发货、对账（可选，视业务规模） |

### 15.3 各目录控制器详细列表

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

### 15.4 路由配置（`routes/` 目录）

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

### 15.5 路由与中间件注册（Laravel 13：`bootstrap/app.php`）

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

### 15.6 自定义中间件

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

### 15.7 zircote/swagger-php Tag 组织

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

### 15.8 命名规范总结

| 层级 | 规范 | 示例 |
|------|------|------|
| **目录** | 大写驼峰（PascalCase） | `Admin/`, `Seller/`, `Shop/` |
| **控制器** | 大写驼峰 + `Controller` | `ProductController.php` |
| **路由文件** | 小写 + `.php` | `routes/api/admin.php` |
| **路由前缀** | 小写 + `v1` | `/api/admin/v1` |
| **路由名** | `{端}.{资源}.{动作}` | `admin.products.index`, `seller.orders.ship` |
| **Tag** | `{端} - {模块}` | `Admin - Products`, `Seller - Finance` |
| **中间件** | `{端}Authenticate` | `admin.auth`, `seller.auth` |

### 15.9 各端 FormRequest 目录结构

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

### 15.10 模型/服务层目录与控制器对应关系

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

### 15.11 多端 OpenAPI 生成策略：按 Tag 分组导出，前端只生成对应端类型

**核心问题**：7 个端的控制器全部扫描后，openapi.json 文件过大（可能 5000+ 行），前端只需要自己端的类型。

**方案：按 Tag 分组过滤，各端独立生成 openapi 文件**

#### 15.11.1 后端：按 Tag 分组生成 OpenAPI

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
        "generate-api:supplier": "php artisan openapi:generate --tag=Supplier --output=../packages/api-contract/openapi-supplier.json",
        "generate-api:shop": "php artisan openapi:generate --tag=Shop --output=../packages/api-contract/openapi-shop.json",
        "generate-api:user": "php artisan openapi:generate --tag=User --output=../packages/api-contract/openapi-user.json",
        "generate-api:portal": "php artisan openapi:generate --tag=Portal --output=../packages/api-contract/openapi-portal.json",
        "generate-api:common": "php artisan openapi:generate --tag=Common --output=../packages/api-contract/openapi-common.json",
        "generate-api:all": "composer generate-api:admin && composer generate-api:seller && composer generate-api:supplier && composer generate-api:shop && composer generate-api:user && composer generate-api:portal && composer generate-api:common"
    }
}
```

#### 15.11.2 前端：按端独立生成类型

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
    { name: 'supplier', openapi: 'openapi-supplier.json', package: 'api-supplier' },
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
        "generate-api:supplier": "tsx scripts/generate-api-types.ts supplier",
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

// apps/seller/package.json
{
    "dependencies": {
        "@phpmall/api-seller": "workspace:*",
        "@phpmall/api-common": "workspace:*",
        "@phpmall/api-portal": "workspace:*"
    }
}

// apps/supplier/package.json
{
    "dependencies": {
        "@phpmall/api-supplier": "workspace:*",
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
├── api-supplier/        # 供应商端契约（Supplier + Common + Portal，可选）
│   ├── openapi-supplier.json
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

#### 15.11.3 完整开发工作流

```bash
# 1. 后端修改接口后，生成各端独立 OpenAPI
cd apps/backend
composer generate-api:all

# 2. 前端重新生成自己端的类型
cd ../../
pnpm generate-api:all

# 3. 或只生成特定端（如 PC 商城只依赖 Shop + User + Common + Portal）
pnpm generate-api:shop && pnpm generate-api:user && pnpm generate-api:common && pnpm generate-api:portal

# 4. Vite+ 自动检测到 api-shop / api-user 变更，重新构建 website
vp run website#build
```

**方案对比**

| 方案 | 文件大小 | 构建速度 | 类型安全 | 适用场景 |
|------|---------|---------|---------|----------|
| **单一 openapi.json** | 大（5000+ 行） | 慢（全量生成） | 中（端类型混杂） | 小型项目，端少 |
| **按端独立 openapi（推荐）** | 小（每端 500-1500 行） | 快（并行生成） | 高（无冗余类型） | **中大型 B2B2C** |
| **前端过滤（不推荐）** | 大（传输全量） | 慢 | 低（构建时过滤，易漏） | 不推荐 |

> **文档结束**  
> 本文档应随项目迭代持续更新，建议在每次架构变更或技术升级后同步修订。

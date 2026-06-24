# B2B2C 商城系统技术方案文档

> **文档版本**：v2.0  
> **编写日期**：2026年6月  
> **适用项目**：PHP B2B2C 多商户电商平台  
> **目标读者**：技术负责人、架构师、后端/前端开发工程师、DevOps 工程师

---

## 目录

1. [技术栈总览](#1-技术栈总览)
2. [后端架构详解](#2-后端架构详解)
3. [数据层架构详解](#3-数据层架构详解)
4. [缓存与高性能层](#4-缓存与高性能层)
5. [消息队列与异步处理](#5-消息队列与异步处理)
6. [支付与财务体系](#6-支付与财务体系)
7. [基础设施与 DevOps](#7-基础设施与-devops)
8. [版本号修正说明](#8-版本号修正说明)
9. [附录](#9-附录)
10. [深度附录：Monorepo 关键配置详解](#10-深度附录monorepo-关键配置详解)

---


## 1. 技术栈总览

### 1.1 分层技术架构图

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

### 1.2 技术选型速查表

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


---

## 2. 后端架构详解

### 2.1 框架选型：Laravel 13 + Octane (Swoole)

#### 2.1.1 为什么选择 Laravel 13？

| 特性 | 说明 |
|------|------|
| **Eloquent ORM** | 强大的 ActiveRecord 模式，支持关联预加载、查询作用域，适合电商复杂查询 |
| **Migration/Seeder** | 数据库版本控制，团队协作必备 |
| **Queue/Job 系统** | 原生支持多种队列驱动，Horizon 提供监控 UI |
| **Event/Listener** | 订单状态变更、支付成功等事件驱动架构 |
| **Policy/Gate** | 细粒度权限控制，适合 B2B2C 多角色场景 |
| **Package 生态** | `spatie/laravel-permission`（RBAC）、`maatwebsite/excel`（导入导出）、`barryvdh/laravel-debugbar` 等 |
| **PHP 8.2+ 特性** | 支持 Enum、Match 表达式、Fiber（Octane 基础） |

#### 2.1.2 Laravel Octane 高性能模式

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

#### 2.1.3 目录结构规范

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

### 2.2 核心服务层设计

#### 2.2.1 服务层模式（Service Layer）

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

### 2.3 异常处理与错误码规范

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


---

## 3. 数据层架构详解

### 3.1 MySQL 主库设计

#### 3.1.1 分库分表策略

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

#### 3.1.2 读写分离配置

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

### 3.2 Elasticsearch 搜索架构

#### 3.2.1 索引设计

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

#### 3.2.2 搜索服务封装

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

### 3.3 MongoDB 文档存储

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


---

## 4. 缓存与高性能层

### 4.1 Redis 缓存架构

#### 4.1.1 缓存分层策略

| 层级 | 缓存内容 | TTL | 更新策略 |
|------|----------|-----|----------|
| **L1 - 应用内存** | Octane 全局变量、配置项 | 常驻 | 监听配置变更事件 |
| **L2 - Redis** | 商品基础信息、库存、购物车、Session | 10min-24h | Cache-Aside |
| **L3 - Nginx 缓存** | 商品详情页、CMS 页面 | 1h-24h | 主动刷新/Purge |
| **L4 - CDN** | 静态资源、图片、JS/CSS | 7d-30d | 文件名 Hash |

#### 4.1.2 缓存 Key 命名规范

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

#### 4.1.3 秒杀库存扣减（Redis + Lua 原子操作）

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

### 4.2 OPcache + JIT 优化

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


---

## 5. 消息队列与异步处理

### 5.1 Laravel Queue + Horizon 架构

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

### 5.2 核心队列任务设计

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

### 5.3 Horizon 配置

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


---

## 6. 支付与财务体系

### 6.1 支付网关架构

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

### 6.2 分账系统（平台-商户资金分离）

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

### 6.3 对账系统

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

### 6.4 虚拟钱包体系

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


---

## 7. 基础设施与 DevOps

### 7.1 Docker 容器化

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

### 7.2 Kubernetes 生产编排

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

### 7.3 监控与告警

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


---

## 8. 版本号修正说明

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


---

## 9. 附录

### 9.1 环境变量模板

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

### 9.2 依赖包清单（composer.json）

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

### 9.3 开发环境快速启动

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


---

## 10. 深度附录：Monorepo 关键配置详解

> **⚠️ 项目说明**：当前项目使用 **Vite+ (vp)** 作为统一工具链，而非 Turborepo。本附录中的前端配置示例（Next.js、Vite、UniApp）为规划设计参考，与实际代码目录的对应关系为：`apps/website`（PC 商城，Next.js）、`apps/admin`（平台管理后台，React + Ant Design）、`apps/seller`（商家后台，React + Ant Design）、`apps/supplier`（供应商后台，React + Ant Design）、`apps/mobile`（移动端，UniApp 3 + Vue 3）、`apps/backend`（Laravel 后端 API）。实施时请以代码仓库为准。

### 10.1 API 类型自动生成脚本（Laravel OpenAPI → TypeScript/Zod）

#### 10.1.1 Laravel 端：使用 zircote/swagger-php 生成 OpenAPI 3.1 规范

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

#### 10.1.1.1 FormRequest 集成：从 rules() 方法自动生成 OpenAPI 参数

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

#### 10.1.1.2 枚举类型（Backed Enum）：PHP 8.4 Enum 映射到 OpenAPI Schema

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

#### 10.1.1.3 分页响应通用 Schema：PaginatedResponse 复用封装

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

#### 10.1.2 类型生成脚本：OpenAPI → TypeScript + Zod

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

#### 10.1.3 生成的类型使用示例

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

### 10.2 Turborepo 远程缓存配置

#### 10.2.1 方案对比

| 方案 | 成本 | 适用场景 | 配置复杂度 |
|------|------|----------|-----------|
| **Vercel Remote Cache** | 免费（团队≤1人）/ 付费 | 中小型团队，快速上手 | 低 |
| **自托管 MinIO + Turbo** | 服务器成本 | 大型团队，数据敏感 | 高 |
| **AWS S3 + Turbo** | 按量计费 | 已有 AWS 基础设施 | 中 |
| **阿里云 OSS + Turbo** | 按量计费 | 国内部署，合规要求 | 中 |

#### 10.2.2 Vercel Remote Cache（推荐快速方案）

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

#### 10.2.3 自托管 MinIO 远程缓存（企业方案）

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

#### 10.2.4 缓存策略优化

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

### 10.3 各应用 Vite/Next.js 具体配置

#### 10.3.1 `apps/website` - Next.js 16 生产配置

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

#### 10.3.2 `apps/admin` - Vite 8.1 + React 19 + Ant Design 6 配置

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

#### 10.3.3 `apps/mobile` - UniApp 3 + Vite 配置

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

#### 10.3.4 `apps/seller` - 商家后台 Vite + React 19 + Ant Design 6 配置

商家后台与平台管理后台技术栈一致，均使用 `apps/admin` 同构的 Vite + React + Ant Design 方案，应用目录为 `apps/seller`，构建产物通过 `vp build` 输出到 `dist/`。

```typescript
// apps/seller/vite.config.ts
import { defineConfig, lazyPlugins } from "vite-plus";
import react from "@vitejs/plugin-react";

export default defineConfig({
  plugins: lazyPlugins(() => [react()]),
});
```

#### 10.3.5 `apps/supplier` - 供应商后台 Vite + React 19 + Ant Design 6 配置

供应商后台为可选端，应用目录为 `apps/supplier`，技术栈与商家后台保持一致，便于统一组件复用和部署流水线。

```typescript
// apps/supplier/vite.config.ts
import { defineConfig, lazyPlugins } from "vite-plus";
import react from "@vitejs/plugin-react";

export default defineConfig({
  plugins: lazyPlugins(() => [react()]),
});
```

#### 10.3.6 `packages/tsconfig` - 共享 TypeScript 配置

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

#### 10.3.5 `packages/eslint-config` - 共享 ESLint 配置

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


---

> **文档结束**  
> 本文档应随技术架构变更持续更新。

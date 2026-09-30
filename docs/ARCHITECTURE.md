# 系统架构与全端数据流向设计规范 (Architecture & Data Flow)

> **版本**：v1.0  
> **更新时间**：2026-09-30  
> **状态**：已审定（作为项目前台与各模块开发基准）

---

## 1. 架构全景与核心设计原则

本项目采用 **多端接入 + 统一领域模型 (Domain-Driven Design) + 前后端清晰分层** 的架构体系：

```
┌────────────────────────────────────────────────────────────────────────┐
│                        终端展现层 (UI / View)                          │
├──────────────────┬───────────────────────┬─────────────────────────────┤
│   PC 前台商城    │   移动多端 (Uni-App)  │    四大用户模块 UI 视图     │
│ Http/Controllers │    packages/mobile    │         app/Modules         │
│  (Blade 页面)    │  (H5/小程序/App)      │ Admin / Seller / Supplier / │
│                  │                       │      User (Blade 视图)      │
└─────────┬────────┴───────────┬───────────┴──────────────┬──────────────┘
          │ (Ajax/Fetch)       │ (HTTP REST)              │ (Ajax/Fetch)
          ▼                    ▼                          ▼
┌────────────────────────────────────────────────────────────────────────┐
│                    API 接口接入层 (app/Api/*)                          │
├──────────────────────────────────────────┬─────────────────────────────┤
│         前台公共与交易接口               │      各角色管理业务接口     │
│           app/Api/Portal                 │  app/Api/{Admin,Seller,     │
│   (PC 前台与移动端多端统一复用)          │       Supplier,User}        │
└─────────────────────────────┬────────────┴──────────────┬──────────────┘
                              │                           │
                              ▼                           ▼
┌────────────────────────────────────────────────────────────────────────┐
│                    核心领域业务层 (app/Domains)                        │
│    Goods(商品)  /  Order(订单交易)  /  Cart(购物车)  /  User(会员)      │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │ 业务规则闭环：库存校验扣减、阶梯定价、下单结算、状态机流转等       │  │
│  └──────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────┬──────────────────────────────────────────┘
                              │
                              ▼
┌────────────────────────────────────────────────────────────────────────┐
│                数据存储与基础设施 (Storage / Infra)                    │
│                     MySQL 8.0  /  Redis 缓存与队列                     │
└────────────────────────────────────────────────────────────────────────┘
```

### 核心设计原则

1. **多端复用 API 资产**：
   - `packages/mobile`（Uni-App 移动端）与 PC 前台商城的页面异步交互，**共同调用 `app/Api/Portal`** 提供的 RESTful 接口，杜绝重复开发业务逻辑。
2. **UI 视图与数据解耦**：
   - PC 前台（`app/Http/Controllers`）与各类用户模块（`app/Modules`）统一采用 **Blade 模板引擎** 渲染页面骨架，页面加载完成后通过 **浏览器端 Ajax/Fetch** 消费各自的 `app/Api` 接口。
3. **领域业务防腐（Domain-Driven）**：
   - 所有 Controller（无论是 Web 页面控制器还是 Api 数据控制器）均作为**薄控制器（Thin Controller）**，仅负责接收参数校验与格式化输出。
   - 核心交易计算、订单状态扭转、库存扣减、分润对账等业务逻辑**严格封装在 `app/Domains` 中**。

---

## 2. 详细数据流向图

### 2.1 全景数据拓扑流向图

```mermaid
flowchart TD
    %% 展现层
    subgraph Clients ["终端与视图展现层 (Presentation Layer)"]
        direction TB
        PC_Portal["🖥️ PC 前台商城<br/>(app/Http/Controllers + Blade)"]
        Mobile_App["📱 移动端 (Uni-App)<br/>(packages/mobile H5/小程序/App)"]
        
        subgraph Modules_UI ["各角色模块 UI (app/Modules)"]
            UI_Admin["🛠️ 平台总管理端 (Modules/Admin)"]
            UI_Seller["🏬 商家管理端 (Modules/Seller)"]
            UI_Supplier["🏭 供应商协同端 (Modules/Supplier)"]
            UI_User["👤 买家个人中心 (Modules/User)"]
        end
    end

    %% 路由分发层
    subgraph RouteLayer ["路由接入与路由组 (Routes Layer)"]
        R_Web["routes/web.php<br/>(提供页面入口 URL)"]
        R_PortalApi["/api/portal/*<br/>(前台商品与交易路由)"]
        R_AdminApi["/api/admin/*<br/>(管理后台路由)"]
        R_SellerApi["/api/seller/*<br/>(商家业务路由)"]
        R_SupplierApi["/api/supplier/*<br/>(供货商路由)"]
        R_UserApi["/api/user/*<br/>(会员中心路由)"]
    end

    %% 数据接口控制器
    subgraph ApiControllers ["数据接口控制器 (app/Api)"]
        C_Portal["app/Api/Portal/Controllers<br/>• 商品检索/类目/详情<br/>• 购物车/下单结算/支付"]
        C_Admin["app/Api/Admin/Controllers<br/>• 系统运营/商品审核/结算审核"]
        C_Seller["app/Api/Seller/Controllers<br/>• 店铺配置/商品发布/订单发货"]
        C_Supplier["app/Api/Supplier/Controllers<br/>• 供货库存/代发订单/对账"]
        C_User["app/Api/User/Controllers<br/>• 收货地址/我的订单/售后申请"]
    end

    %% 核心领域模型与业务层
    subgraph Domains ["核心领域与业务逻辑 (app/Domains)"]
        D_Goods["📦 Goods Domain (商品模型/SKU/库存/规格)"]
        D_Order["💳 Order Domain (购物车/算价/创建订单/状态机)"]
        D_User["👥 User Domain (账户认证/会员权益/地址库)"]
        D_Supplier["🚚 Supplier Domain (供应商供货/分销采购)"]
    end

    %% 持久层
    subgraph Storage ["存储与缓存基础设施"]
        DB[(MySQL 数据库)]
        Cache[(Redis 缓存/分布式锁/队列)]
    end

    %% 流程关系连接
    Browser_User((用户浏览器)) -->|"GET /、/goods/:id、/cart"| R_Web
    R_Web --> PC_Portal
    PC_Portal -.->|"渲染页面加载完成"| Browser_User

    Browser_User -->|"Ajax/Fetch 请求数据"| R_PortalApi
    Mobile_App -->|"HTTP/JSON 请求数据"| R_PortalApi

    UI_Admin -->|"Ajax/Fetch"| R_AdminApi
    UI_Seller -->|"Ajax/Fetch"| R_SellerApi
    UI_Supplier -->|"Ajax/Fetch"| R_SupplierApi
    UI_User -->|"Ajax/Fetch"| R_UserApi

    R_PortalApi --> C_Portal
    R_AdminApi --> C_Admin
    R_SellerApi --> C_Seller
    R_SupplierApi --> C_Supplier
    R_UserApi --> C_User

    C_Portal --> D_Goods & D_Order & D_User
    C_Admin --> D_Goods & D_Order & D_User & D_Supplier
    C_Seller --> D_Goods & D_Order
    C_Supplier --> D_Supplier & D_Order
    C_User --> D_User & D_Order

    Domains --> DB
    Domains --> Cache
```

---

### 2.2 典型交易时序图（前台浏览 -> 下单 -> 支付）

展示 PC 端与移动端如何统一复用 `app/Api/Portal` 与 `app/Domains`：

```mermaid
sequenceDiagram
    autonumber
    actor PC as PC 用户 (浏览器)
    actor Mobile as 移动端用户 (Uni-App)
    participant PageController as app/Http/Controllers (PC页面)
    participant PortalApi as app/Api/Portal/Controllers (接口层)
    participant OrderDomain as app/Domains/Order (领域业务)
    participant GoodsDomain as app/Domains/Goods (领域业务)
    participant DB as MySQL / Redis

    Note over PC, PageController: 1. 页面浏览阶段 (仅 PC 前台有此步)
    PC ->> PageController: 访问商品页 GET /goods/1001
    PageController -->> PC: 返回 Blade HTML 骨架 (来自 resources/views)

    Note over PC, Mobile: 2. 异步获取商品与价格数据 (多端共用 Api/Portal)
    par PC 页面异步获取
        PC ->> PortalApi: GET /api/portal/goods/1001
    and 移动端原生请求
        Mobile ->> PortalApi: GET /api/portal/goods/1001
    end
    PortalApi ->> GoodsDomain: 查询商品详情、SKU规格及库存
    GoodsDomain ->> DB: 读取缓存/数据库
    DB -->> GoodsDomain: 返回数据
    GoodsDomain -->> PortalApi: 组装输出 DTO / Resource
    PortalApi -->> PC: 返回 JSON (渲染商品详情)
    PortalApi -->> Mobile: 返回 JSON (渲染商品详情)

    Note over PC, Mobile: 3. 提交订单交易阶段 (多端共用交易接口)
    PC ->> PortalApi: POST /api/portal/order/create (SKU, 数量, 地址ID)
    Mobile ->> PortalApi: POST /api/portal/order/create (SKU, 数量, 地址ID)
    PortalApi ->> OrderDomain: 提交下单请求
    OrderDomain ->> GoodsDomain: 预占/锁定库存
    OrderDomain ->> OrderDomain: 计算优惠券、运费、阶梯折扣
    OrderDomain ->> DB: 开启事务，持久化 Order & OrderItems 表
    DB -->> OrderDomain: 事务提交成功
    OrderDomain -->> PortalApi: 返回订单编号与应付金额
    PortalApi -->> PC: 返回下单成功 JSON -> 跳转收银台
    PortalApi -->> Mobile: 返回下单成功 JSON -> 发起微信/支付宝支付
```

---

## 3. 模块职责与目录映射规范

| 模块定位 | 对应代码路径 | 主要职责与规范 | 数据交互目标 |
| :--- | :--- | :--- | :--- |
| **PC 前台页面** | `app/Http/Controllers/` | 负责前台商城页面的 HTTP 入口，返回 Blade 视图（复用 `resources/html` 切片样式） | 页面内前端 Ajax 访问 `app/Api/Portal` |
| **移动多端** | `packages/mobile/` | Uni-App Vue3 移动端工程，构建 H5、微信小程序与移动 App | HTTP 请求 `app/Api/Portal` |
| **各用户模块 UI** | `app/Modules/{Module}/` | 包含 `Admin`, `Seller`, `Supplier`, `User` 模块的控制器与 UI 骨架视图 | 页面内前端 Ajax 访问对应 `app/Api/{Module}` |
| **数据接口 API** | `app/Api/{Module}/` | 统一 RESTful API 控制器与路由定义，提供标准化 JSON 响应 | 调用 `app/Domains` 业务服务 |
| **业务领域模型** | `app/Domains/{Domain}/` | 沉淀高内聚的业务逻辑、模型实体、状态机、仓储接口与计算规则 | 读写 MySQL 与 Redis |

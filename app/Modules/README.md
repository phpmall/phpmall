# PHPMall 领域模块（app/Modules）

本目录按照领域驱动设计（DDD）思想，将 B2B2C 电商系统拆分为高内聚、低耦合的 Laravel 模块。每个模块拥有独立的目录结构，可独立演进、独立测试。

## 模块清单

### 核心域（Core Domain）

| 模块 | 说明 |
|------|------|
| `Merchant` | 商户域：入驻、资质、结算账户、违规冻结 |
| `Shop` | 店铺域：店铺信息、装修、运费模板、店铺评价 |
| `Product` | 商品域：SPU/SKU、类目、品牌、属性、审核 |
| `Inventory` | 库存域：SKU 库存、多仓、库存流水、预占 |
| `Order` | 订单域：购物车、拆单、状态机、履约、评价 |
| `Payment` | 支付域：支付网关、支付单、分账、对账 |
| `Marketing` | 营销域：优惠券、满减、秒杀、积分商城 |
| `Distribution` | 分销域：分销员、关系树、佣金、提现 |

### 支撑域（Supporting Domain）

| 模块 | 说明 |
|------|------|
| `Store` | 门店域：O2O 线下门店、自提点、配送范围 |
| `Refund` | 退款售后域：退款/退货/换货、仲裁 |
| `Logistics` | 物流域：发货单、物流轨迹、包裹 |
| `Finance` | 财务域：钱包、结算单、抽佣、对账 |
| `Content` | 内容域：Banner、公告、CMS、协议、素材 |
| `Supplier` | 供应商域：供货商品、采购、供货结算 |
| `PlatformOperation` | 平台运营域：审核、强制下架、运营任务、仪表盘 |
| `Search` | 搜索域：商品检索、过滤、排序、建议 |

### 通用域（Generic Domain）

| 模块 | 说明 |
|------|------|
| `Auth` | 认证授权域：登录、RBAC、数据范围 |
| `Notification` | 通知域：短信、邮件、站内信、推送 |
| `System` | 系统配置域：配置项、字典、基础审计日志 |
| `AuditLog` | 审计日志域：跨模块审计事件、WORM 存储、合规报表 |

### 合规域（Compliance Domain）

| 模块 | 类型 | 说明 |
|------|------|------|
| `Compliance` | 核心域 | 合规风控域：KYC、AML、反欺诈、制裁名单筛查 |
| `DataPrivacy` | 核心域 | 数据合规与隐私域：PIPL/GDPR、同意管理、数据主体权利 |
| `ProductCompliance` | 核心域 | 商品合规与内容审核域：违禁品、侵权、广告法、资质准入 |
| `Escrow` | 核心域 | 资金存管域：资金隔离、保证金、规避二清 |
| `Invoice` | 支撑域 | 发票税务域：电子发票、红冲、代扣代缴、纳税申报 |
| `Contract` | 支撑域 | 电子合同与法务域：电子签名、合同存证、法务档案 |
| `ConsumerProtection` | 支撑域 | 消费者权益与纠纷调解域：投诉、调解、先行赔付 |

### 已存在模块

| 模块 | 说明 |
|------|------|
| `User` | 用户域：C 端用户资料、地址、会员 |

> 注：原 `Admin` 空模块已并入 `PlatformOperation` 平台运营域，平台运营后台前端位于 `packages/admin`。

## 目录规范

每个模块统一包含以下目录：

```
app/Modules/{Domain}/
├── Database/
│   ├── factories/      # 模型工厂
│   ├── migrations/     # 数据迁移
│   └── seeders/        # 数据填充
├── Entities/           # 领域实体 / 聚合根
├── Http/
│   ├── Controllers/    # 控制器
│   ├── Middleware/     # 中间件
│   ├── Requests/       # 请求 DTO / 表单验证
│   └── Responses/      # 响应 DTO
├── Models/             # Eloquent 模型
├── Providers/          # 模块服务提供者
├── Repositories/       # 仓储层
├── Resources/
│   └── Views/          # 视图 / 模板
├── Routes/
│   └── web.php         # 模块路由
└── Services/           # 应用服务 / 领域服务
```

## 新增模块步骤

1. 在 `app/Modules/` 下新建目录；
2. 创建 `Providers/{Domain}ServiceProvider.php`；
3. 将 Provider 注册到 `bootstrap/providers.php`；
4. 运行 `composer dump-autoload`；
5. 按需补充 `Entities`、`Models`、`Repositories`、`Services`、`Http`、`Routes`、`Database`。

## 依赖关系

各模块应通过领域事件、仓储接口或应用服务进行交互，避免跨模块直接操作数据表。详细依赖关系见各模块 `README.md` 的“依赖领域”章节。

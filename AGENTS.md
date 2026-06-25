# AGENTS

> PHPMall B2B2C 多商户电商平台 — AI 研发助手指令文件
> 
> 本文档是 AI Agent 的中央入口，涵盖项目架构、研发规范、常用命令与约定。各领域模块的细节请参阅对应 `app/Modules/{Domain}/README.md` 及 `docs/` 下的专题文档。

---

## 一、项目概览

| 维度 | 说明 |
|------|------|
| 项目名称 | PHPMall B2B2C 多商户电商平台 |
| 项目类型 | PHP Monorepo（后端 DDD 模块 + 前端 5 端） |
| PHP 版本 | `^8.4` |
| 框架 | Laravel 13.8 + Octane (Swoole) |
| 前端技术 | PC 商城：Laravel Blade + Vite + Tailwind CSS；SPA 端：Vue 3 + Vite + Pinia + vue-router；移动端：UniApp 3 |
| 包管理器 | Composer (PHP) + pnpm (JS Monorepo) |
| 数据库 | MySQL 8.4, Redis, Elasticsearch 9.x |
| 核心第三方 | 微信支付/支付宝 (yansongda/laravel-pay)、微信 SDK (overtrue/laravel-wechat)、短信 (overtrue/easy-sms) |

## 二、目录结构

```
phpmall/
├── app/
│   ├── Modules/                  # 【核心】领域模块（DDD，28 个模块）
│   │   ├── Auth/                 # 认证授权域
│   │   ├── User/                 # C 端用户域（资料、地址、会员）
│   │   ├── Merchant/             # 商户域（入驻、资质、冻结）
│   │   ├── Shop/                 # 店铺域（信息、运费模板）
│   │   ├── Store/                # O2O 门店域（自提点、配送范围）
│   │   ├── Product/              # 商品域（SPU/SKU、类目、品牌）
│   │   ├── Inventory/            # 库存域（扣减、预占、多仓）
│   │   ├── Order/                # 订单域（购物车、拆单、状态机）
│   │   ├── Payment/              # 支付域（统一网关、分账、对账）
│   │   ├── Refund/               # 退款售后域（退货/换货、仲裁）
│   │   ├── Logistics/            # 物流域（发货、轨迹查询）
│   │   ├── Marketing/            # 营销域（优惠券、满减、秒杀）
│   │   ├── Distribution/         # 分销域（三级分销、佣金）
│   │   ├── Finance/              # 财务域（钱包、结算、提现）
│   │   ├── Content/              # 内容域（Banner、公告、CMS）
│   │   ├── Supplier/             # 供应商域（供货、采购）
│   │   ├── PlatformOperation/    # 平台运营域（审核、仪表盘、仲裁）
│   │   ├── Search/               # 搜索域（Elasticsearch 全文检索）
│   │   ├── Notification/         # 通知域（短信、邮件、站内信）
│   │   ├── System/               # 系统配置域（参数、字典、操作日志）
│   │   ├── AuditLog/             # 审计日志域（WORM、合规报表）
│   │   ├── Compliance/           # 合规风控域（KYC、AML、反欺诈）
│   │   ├── DataPrivacy/          # 数据合规与隐私域（PIPL/GDPR）
│   │   ├── ProductCompliance/    # 商品合规与内容审核域
│   │   ├── Escrow/               # 资金存管域（二清合规）
│   │   ├── Invoice/              # 发票税务域（电子发票、代扣代缴）
│   │   ├── Contract/             # 电子合同与法务域
│   │   ├── ConsumerProtection/   # 消费者权益与纠纷调解域
│   │   └── README.md             # 模块清单与目录规范
│   └── Providers/                # Laravel 应用级服务提供者
├── bootstrap/
│   └── providers.php             # 【关键】模块 ServiceProvider 注册文件
├── config/                       # Laravel 配置
├── database/                     # 全局迁移、工厂、填充
├── docs/                         # 项目文档（PRD、架构、DB 设计、API 契约等）
├── packages/                     # 【前端 Monorepo（pnpm workspace）】
│   ├── admin/                    # 平台管理后台（Vue 3 + Vite）
│   ├── seller/                   # 商家后台（Vue 3 + Vite）
│   ├── supplier/                 # 供应商后台（Vue 3 + Vite）
│   ├── user/                     # 买家会员中心（Vue 3 + Vite）
│   └── mobile/                   # 移动端（UniApp 3，H5/小程序/App，vue-i18n）
├── resources/views/              # PC 商城（Laravel Blade 模板引擎）
├── routes/                       # Laravel 路由（含 PC 商城页面路由）
├── tests/                        # 全局测试
├── AGENTS.md                     # 本文件
├── docs/TODO.md                  # 全项目开发 TODO 清单
├── composer.json
├── package.json
├── pnpm-workspace.yaml
└── vite.config.js
```

## 三、领域模块规范

### 3.1 模块分层（DDD）

每个模块内部遵循统一的 DDD 分层，目录结构如下：

```
app/Modules/{Domain}/
├── Database/
│   ├── factories/       # 模型工厂
│   ├── migrations/      # 数据迁移
│   └── seeders/         # 数据填充
├── Entities/            # 领域实体 / 聚合根（纯业务逻辑，不依赖框架）
├── Http/
│   ├── Controllers/     # 控制器
│   ├── Middleware/      # 中间件
│   ├── Requests/        # 请求 DTO / 表单验证
│   └── Responses/       # 响应 DTO / API 资源
├── Models/              # Eloquent 模型（数据持久化）
├── Providers/           # 模块 ServiceProvider
├── Repositories/        # 仓储层（数据访问抽象）
├── Resources/Views/     # 视图模板（如有）
├── Routes/web.php       # 模块路由
└── Services/            # 应用服务 / 领域服务
```

### 3.2 模块类型与优先级

| 类型 | 模块 | Phase |
|------|------|-------|
| **核心域** | Merchant, Shop, Product, Inventory, Order, Payment, Marketing, Distribution | Phase 1 |
| **核心合规域** | Compliance, DataPrivacy, ProductCompliance, Escrow | Phase 3 |
| **支撑域** | Store, Refund, Logistics, Finance, Content, Supplier, PlatformOperation, Search | Phase 2 |
| **合规支撑域** | Invoice, Contract, ConsumerProtection | Phase 3 |
| **通用域** | Auth, Notification, System, AuditLog | Phase 0~2 |
| **用户域** | User | Phase 1 |

### 3.3 新增模块 Checklist

1. 在 `app/Modules/` 下创建 `{Domain}/` 目录
2. 创建 `Providers/{Domain}ServiceProvider.php`
3. 在 `bootstrap/providers.php` 中注册 Provider
4. 运行 `composer dump-autoload`
5. 按需创建 Entities、Models、Repositories、Services、Http、Routes、Database 子目录
6. 编写 `README.md` 说明模块职责与依赖

### 3.4 模块间通信

- **优先使用领域事件**（Laravel Event）进行模块解耦
- 通过仓储接口或应用服务进行跨模块调用
- **禁止**跨模块直接操作其他模块的数据表

---

## 四、研发流程（三步黄金流程）

### 第 1 步：领域建模 + 接口契约先行（1-3 天）

**产出物：**
- 领域模型图（聚合根、实体、值对象、领域事件）
- API 契约（OpenAPI / Scramble 注解）

**关键原则：接口按「前端用例」设计，不按「领域方法」设计。**

契约一旦确认，前后端解耦：
- 前端按照契约写页面、调接口（可用 Mock 数据）
- 后端按照契约实现 DDD 代码

### 第 2 步：后端 DDD 开发 + 前端 Mock 开发（并行）

| 后端（DDD） | 前端（Mock） |
|-------------|-------------|
| 实现聚合根（Entity） | 根据契约生成 Mock 数据 |
| 实现 Repository | 用 Mock 数据渲染页面 |
| 实现领域服务（业务规则） | 实现前端交互逻辑 |
| 实现应用层（事务管理） | 对接 Mock 接口 |
| 编写单元测试（领域逻辑） | 编写 E2E 测试（页面流程） |
| **关键点：领域层不依赖任何外部技术** | **关键点：不等待后端接口** |

### 第 3 步：联调 + 集成测试（1-2 天）

1. 后端将接口部署到测试环境
2. 前端将 Mock 地址切换为真实后端地址
3. 接口字段对齐（契约已定，几乎无改动）
4. 执行集成测试流程：注册 → 登录 → 下单 → 支付 → 库存 → 通知

### 正确的时间线（契约先行）

```
1: 前后端 + PM 开会，定义 API 契约
2: 后端 → 写 DDD 代码 | 前端 → 根据契约 Mock + 写页面
3: 后端单元测试通过，部署测试环境
4: 前端切换真实接口，联调 ≤1 小时完成
```

---

## 五、编码规范

### 5.1 PHP

| 规范 | 工具 |
|------|------|
| 代码风格 | PSR-12，`./vendor/bin/pint` |
| 静态分析 | PHPStan Level 8+，`./vendor/bin/phpstan analyse` |
| 类型声明 | 所有方法必须有参数类型与返回类型 |
| 命名 | 类名 PascalCase，方法/属性 camelCase，表名 snake_case 复数 |

### 5.2 前端

| 端 | 技术 | 状态管理/路由 |
|----|------|---------------|
| PC 商城 | Laravel Blade + Vite + Tailwind CSS | 服务端渲染，无前端路由 |
| 买家会员中心 (`packages/user`) | Vue 3 + TypeScript + Vite | Pinia + vue-router |
| 移动端 (`packages/mobile`) | UniApp 3（H5 + 微信小程序 + App）+ vue-i18n | UniApp 内置 |
| 后台三端 (`packages/admin/seller/supplier`) | Vue 3 + TypeScript + Vite | Pinia + vue-router |
| 共享能力 | 网络请求、状态管理（Pinia）、i18n、埋点 | 公共 composables/utils |

| 工具 | 说明 |
|------|------|
| 类型检查 | `vue-tsc --build` |
| 单元测试 | Vitest |
| E2E 测试 | Playwright |
| 代码检查 | ESLint + Oxlint |
| 格式化 | Prettier |

### 5.3 Git 规范

- **分支策略**：Git Flow（`master` / `develop` / `feature/*` / `hotfix/*`）
- **提交信息**：`type(scope): description`（如 `feat(product): 实现 SPU/SKU 模型`）
- **Code Review**：所有合并需 MR + 至少一人 Review
- **预提交检查**：lint + format（通过 Git hooks 自动执行）

---

## 六、常用命令

### 后端

```bash
# 本地开发环境
./vendor/bin/sail up -d                    # 启动 Docker 开发环境
./vendor/bin/sail artisan migrate:fresh --seed  # 重建数据库 + 填充 Demo 数据

# 代码质量
./vendor/bin/pint                          # 代码格式化（PSR-12）
./vendor/bin/phpstan analyse               # 静态分析
./vendor/bin/phpunit                       # 运行全部测试
./vendor/bin/phpunit --filter=OrderTest    # 运行指定测试

# 模块操作
php artisan make:module {Domain}           # 创建新模块骨架
composer dump-autoload                     # 刷新 autoload（新增模块后必须执行）
php artisan optimize:clear                 # 清理缓存

# 队列与调度
php artisan queue:work                     # 启动队列 worker
php artisan schedule:work                  # 启动任务调度
php artisan horizon                        # Horizon 队列监控

# IDE 辅助
php artisan ide-helper:generate            # 生成 IDE 辅助文件
php artisan ide-helper:models              # 生成模型 DocBlock
```

### 前端

```bash
# 安装依赖
pnpm install                               # 安装所有 workspace 依赖

# 启动开发服务器
pnpm --filter phpmall-user dev             # 买家会员中心（Vue 3 + Vite）
pnpm --filter phpmall-admin dev            # 平台管理后台（Vue 3 + Vite）
pnpm --filter phpmall-seller dev           # 商家后台（Vue 3 + Vite）
pnpm --filter phpmall-supplier dev         # 供应商后台（Vue 3 + Vite）
pnpm --filter phpmall-mobile dev:h5        # 移动端 H5
pnpm --filter phpmall-mobile dev:mp-weixin # 移动端微信小程序

# 构建
pnpm --filter phpmall-admin build          # 构建指定端
pnpm --filter phpmall-mobile build:h5      # 构建移动端 H5

# 代码检查
pnpm --filter phpmall-admin lint           # ESLint + Oxlint
pnpm --filter phpmall-admin format         # Prettier
pnpm --filter phpmall-admin test:unit      # Vitest 单元测试
pnpm --filter phpmall-admin test:e2e       # Playwright E2E 测试
```

---

## 七、开发约定与注意事项

### 7.1 模块开发

- ✅ **新功能先确定归属模块**，不要直接在全局 `app/` 下添加业务代码
- ✅ **Entities 保持纯 PHP**，不继承 Eloquent Model，不含框架依赖
- ✅ **Models 只做数据映射**，关联、访问器、类型转换放这里
- ✅ **Repositories 封装查询**，不直接在 Controller/Service 中写 `::where()` 链
- ✅ **多端控制器**：在模块 `Http/Controllers/` 下按终端分子目录（如 `Admin/`、`Seller/`）
- ⚠️ **跨模块依赖**：Finance（钱包）→ Payment（余额支付）→ Marketing（优惠分摊）→ Distribution（佣金结算），按此顺序实现

### 7.2 支付与财务

- **支付回调必须验签 + 幂等处理**，不能因重复回调导致重复入账
- **金额计算使用 `int`（分）**，禁止用 `float`
- **退款金额 ≤ 实付金额**，必须校验
- **分账/对账**：每日自动对账，差异标记并告警

### 7.3 安全基线

| 维度 | 要求 |
|------|------|
| 传输 | 全站 HTTPS，TLS 1.2+ |
| 密码 | bcrypt（cost ≥ 12） |
| 敏感数据 | 身份证、银行卡号 AES-256-GCM 加密存储 |
| 支付 | 回调验签 + 请求幂等 + 金额防篡改 |
| 权限 | RBAC + merchant_id 数据隔离 |
| 日志 | 敏感操作全量审计记录，保留 ≥ 180 天 |

### 7.4 数据库约定

- 表名：snake_case 复数（`products`, `order_items`）
- 主键：`bigint unsigned auto_increment`
- 软删除：统一使用 `deleted_at`（`SoftDeletes` trait）
- 时间戳：`created_at`, `updated_at`（`timestamps()`）
- 索引命名：`idx_{table}_{column}`，唯一索引 `udx_{table}_{column}`
- 外键：仅在核心一致性场景使用，高并发表用应用层约束替代

---

## 八、关键配置文件索引

| 文件 | 用途 | 修改频率 |
|------|------|----------|
| `bootstrap/providers.php` | 模块注册（新增模块必改） | 中 |
| `composer.json` | PHP 依赖与 autoload | 低 |
| `config/` | Laravel 全局配置 | 低 |
| `pnpm-workspace.yaml` | 前端 Monorepo 工作区 | 低 |
| `vite.config.js` | Vite 构建配置 | 低 |
| `.env.example` | 环境变量模板 | 低 |

---

## 九、相关文档速查

| 文档 | 内容 |
|------|------|
| `docs/TODO.md` | 全项目开发任务清单（Phase 0~6） |
| `docs/B2B2C-需求文档.md` | 业务功能需求（BRD） |
| `docs/B2B2C-PRD产品文档.md` | 产品设计详设 |
| `docs/B2B2C-技术架构文档-总纲归档.md` | 技术架构总纲 |
| `docs/B2B2C-数据库设计文档.md` | 数据库 ERD 与表结构 |
| `docs/B2B2C-API接口契约文档.md` | API 接口契约 |
| `docs/B2B2C-安全设计文档.md` | 安全架构与策略 |
| `docs/B2B2C-合规审查与补充建议.md` | 财务法务合规 Gap 分析 |
| `docs/B2B2C-技术方案文档.md` | 各领域技术实现方案 |
| `docs/B2B2C-实施方案文档.md` | 分阶段实施计划 |
| `docs/B2B2C-验收方案文档.md` | 测试与验收标准 |
| `app/Modules/README.md` | 模块清单与 DDD 目录规范 |

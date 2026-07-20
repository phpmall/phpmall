# API 接口迭代计划设计文档

> **文档版本**：v1.0  
> **编写日期**：2026/07/01  
> **适用项目**：PHPMall B2B2C 多商户电商平台  
> **设计来源**：基于 `docs/B2B2C-API接口完整度审计报告.md` 制定  
> **计划周期**：8 周（3 个 Sprint：Sprint 0 占 1 周，Sprint 1/2 各占 3 周，Sprint 3 占 1 周）  
> **关键流程约束**：
> 1. 数据库迁移文件统一放在 `database/migrations/`，按模块将同模块所有表写入同一个迁移文件。
> 2. 当前仅 `Auth`、`User` 模块已生成 Service/Repository；其余模块需在 Sprint 0 先完成数据表设计，再运行 `gen:model/service/dao` 生成代码。
> 3. 整体开发顺序：**模块数据表设计 → `php artisan gen:xxx` 生成 Model/Service/Repository → API 接口实现。**

---

## 一、设计目标

基于 `docs/B2B2C-API接口完整度审计报告.md` 的审计结论，当前非 Admin API 通道（Common / Portal / Seller / Shop / Supplier / User）存在「接口骨架完整、但业务逻辑大面积空壳」的问题。本迭代计划的目标是在 8 周内：

1. 完成全站认证统一（JWT Bearer RS256）和公共基础能力（上传、Token 刷新、支付网关抽象）。
2. 实现 User / Shop / Seller / Portal 四个前端通道的 P0 接口。
3. 实现上述通道 80% 以上的 P1 接口。
4. 每个业务域完成后与对应前端 package 联调，确保接口可用。
5. 同步更新接口契约文档与 OpenAPI 注解。

---

## 二、总体约束

| 约束项 | 决策 |
|--------|------|
| 团队规模 | 5 人 |
| 迭代周期 | 8 周 |
| 覆盖范围 | User / Shop / Seller / Portal 四个通道；Supplier / 合规域延后 |
| 认证方式 | 统一为 JWT Bearer（RS256），Sprint 0 完成替换 |
| 拆分维度 | 按业务域拆分（基础设施 / 商品域 / 交易域 / 补齐域），每个域覆盖多通道 |
| 联调方式 | 每个业务域完成后即与对应前端 package 联调 |
| 领域层 | `app/Modules` 中 Service / Repository 需 Sprint 0 先通过 `gen:model/service/dao` 生成；当前仅 Auth/User 已生成 |

---

## 三、时间线总览

```
Week:    0.5 1   2   3   4   5   6   7   8
Sprint:  [ 0 ][     1      ][     2      ][3]
Focus:   JWT  商品域            交易域            补齐/联调
         认证  SKU/搜索/评价     购物车/订单/支付     优惠券/地址/子账号
              Portal首页        退款/售后          店铺首页/联调收尾
```

| Sprint | 周期 | 主题 | 核心交付 |
|--------|------|------|----------|
| **Sprint 0** | 第 0.5-1 周 | 基础设施 | 补充 Product/Order/Payment/Refund/Marketing/Shop/Merchant/Notification 迁移表；生成对应 Model/Service/Repository；JWT 统一、`auth/refresh`、上传、支付网关 mock、空壳标记规范 |
| **Sprint 1** | 第 2-4 周 | 商品域 | C 端商品/SKU/评价/搜索、商家商品 CRUD、Portal 首页聚合 |
| **Sprint 2** | 第 5-7 周 | 交易域 | 购物车 → 结算预览 → 订单 → 支付 → 发货 → 收货/退款全链路 |
| **Sprint 3** | 第 8 周 | 补齐与联调 | 优惠券、地址/省市区、消息通知、子账号权限、店铺首页、收尾 |

---

## 四、团队分工

| 角色 | 人数 | 负责域 | 主要产出 |
|------|------|--------|----------|
| **基础设施负责人** | 1 人 | JWT 认证、`auth/refresh`、公共上传、支付网关抽象 | 统一 Auth Service、Common 通道 P0、支付模块接口 |
| **商品域负责人** | 1 人 | 商品 SPU/SKU、分类、搜索、评价 | Shop / Portal / Seller 商品相关接口 |
| **交易域负责人** | 1 人 | 购物车、订单、售后/退款 | User / Seller 订单与售后接口 |
| **门户与营销负责人** | 1 人 | Portal 首页、Banner、优惠券、店铺首页 | Portal / Shop 营销相关接口 |
| **联调与质量负责人** | 1 人 | 跨通道联调、接口测试、OpenAPI 同步、CI 检查 | 联调报告、接口测试用例、契约更新 |

**协作方式**：
- 每日 15 分钟站会同步阻塞点
- 每周五 demo 当前完成接口
- 每 Sprint 结束进行接口契约 review

---

## 五、Sprint 0：基础设施（第 0.5-1 周）

### 5.1 目标
让后续 7 周的所有开发都在统一、可验证的基础上进行。

### 5.2 任务清单

| 任务 | 负责人 | 验收标准 |
|------|--------|----------|
| 统一 JWT Bearer（RS256）认证 | 基础设施 | User / Seller / Supplier BaseController 改用 `auth:jwt`；Token payload 包含 `sub/type/merchant_id/jti` |
| 新增 `POST /common/v1/auth/refresh` | 基础设施 | 用旧 access_token 换发新 token，验证 jti 黑名单 |
| 空壳 Controller 标记规范 | 基础设施 | 所有空壳方法改为 `throw new NotImplementedException('TODO: ...')` 或 `501` 响应 |
| 上传基础能力 | 基础设施 | `UploadController@image/file/ossPolicy` 接入文件存储 / OSS Policy |
| 支付网关抽象 | 基础设施 | 在 `app/Modules/Payment` 定义 `PaymentGatewayInterface`，API 层可调通 mock 支付 |
| 联调环境准备 | 联调质量 | 每个前端 package 能请求本地 API，OpenAPI 文档可生成 |

### 5.3 风险
JWT 替换可能影响已实现的 `AuthController` / `AddressController` 逻辑，需要回归测试。

---

## 六、Sprint 1：商品域（第 2-4 周）

### 6.1 目标
C 端能看商品、搜商品、看评价；商家能发布商品；Portal 首页能展示内容。

### 6.2 核心任务

| 业务域 | 涉及通道 | 关键接口 |
|--------|----------|----------|
| 商品 SPU/SKU | Shop / Portal / Seller | `GET /shop/v1/products/{id}/skus`、`GET /portal/v1/products/{id}/reviews`、Seller Product CRUD |
| 商品搜索 | Shop / Portal | `GET /search/products`、`GET /search/suggest`、`GET /search/filters` |
| 分类与首页 | Portal / Shop | `GET /portal/v1/regions?parent_code=0`、`GET /portal/v1/` 首页聚合、`GET /shop/v1/categories/tree` |
| 商家商品管理 | Seller | `ProductController@store/update/onShelf/offShelf`、`ProductSkuController` |
| 评价 | Shop / User | `GET /shop/v1/products/{id}/reviews`、`POST /user/v1/order-reviews` |

### 6.3 联调目标
- PC 商城 / Mobile 能打开首页、查看商品详情、看到 SKU 联动
- Seller 后台能完成商品发布与上下架

### 6.4 依赖
- Sprint 0 的 JWT 认证必须完成
- `app/Modules/Product` 的 Service 必须可用

---

## 七、Sprint 2：交易域（第 5-7 周）

### 7.1 目标
打通购物车 → 结算预览 → 订单创建 → 支付 → 商家发货 → 用户确认收货 → 退款/售后全链路。

### 7.2 核心任务

| 业务域 | 涉及通道 | 关键接口 |
|--------|----------|----------|
| 购物车 | User | `GET/POST/PUT/DELETE /user/v1/cart`、`POST /cart/clear`、`POST /cart/batch` |
| 结算预览 | User | `POST /user/v1/orders/preview` | 需登录，放在 User 通道 |
| 订单创建与状态机 | User / Seller | `POST /user/v1/orders`、`GET /user/v1/orders/{id}`、`POST /orders/{id}/cancel/confirm` |
| 支付 | Common / User | `POST /user/v1/payments`、`GET /payments/{id}`、`POST /common/v1/{channel}/notify` |
| 商家订单处理 | Seller | `GET /seller/v1/orders`、`POST /orders/{id}/ship`、`POST /orders/{id}/remark` |
| 退款/售后 | User / Seller | `POST /user/v1/refunds`、`POST /seller/v1/refunds/{id}/audit` |

### 7.3 联调目标
- Mobile / PC 商城能加购物车、提交订单、调起支付、完成支付回调
- Seller 后台能看到新订单、点击发货
- 用户能申请退款，商家能审核退款

### 7.4 依赖
- Sprint 1 的商品/SKU 必须完成
- Sprint 0 的支付网关抽象必须完成

---

## 八、Sprint 3：补齐与联调收尾（第 8 周）

### 8.1 目标
完成 P1 能力，修复跨通道联调问题，形成可演示版本。

### 8.2 核心任务

| 业务域 | 涉及通道 | 关键接口 |
|--------|----------|----------|
| 优惠券 | User / Shop / Seller | `POST /coupons/receive`、`GET /coupons/my`、Seller 优惠券 CRUD |
| 地址与省市区 | User / Portal | `GET /user/v1/addresses`、`GET /portal/v1/regions?parent_code=0` |
| 消息通知 | User / Seller | `GET /messages`、`GET /notifications`、标记已读 |
| 子账号权限 | Seller | `GET/PUT/DELETE /sub-accounts/{id}`、`PUT /sub-accounts/{id}/permissions` |
| 店铺首页 | Portal / Shop | `GET /portal/v1/shops/{id}/home`、`GET /shop/v1/shops/{id}/products` |
| 跨通道联调修复 | 全通道 | 修复字段不一致、权限越界、OpenAPI 不同步问题 |

### 8.3 缓冲用途
- 如果 Sprint 2 交易域延期，第 8 周优先收尾交易链路
- 如果 Sprint 2 顺利，第 8 周重点做 P1 功能和前端体验打磨

---

## 九、验收标准

### 9.1 每个 Sprint 结束时的验收标准

| Sprint | 验收标准 |
|--------|----------|
| **Sprint 0** | ① JWT 登录/刷新/鉴权在 User/Seller/Supplier 跑通；② 空壳接口返回 501；③ 上传接口能返回真实 URL；④ 支付网关 mock 可调通 |
| **Sprint 1** | ① C 端能浏览商品详情并切换 SKU；② 搜索接口返回结果；③ Portal 首页聚合数据非空；④ Seller 能发布商品并上下架 |
| **Sprint 2** | ① 购物车 → 订单 → 支付 → 发货 → 确认收货全链路跑通；② 退款申请/审核流程跑通；③ 订单状态机状态转换正确 |
| **Sprint 3** | ① 优惠券领取/使用/商家创建跑通；② 省市区/地址接口可用；③ 子账号权限可分配；④ 所有 P0 接口单元测试通过 |

### 9.2 全局质量门禁

- PHPStan level 6 无新增错误
- Pint 代码风格检查通过
- 新增接口必须补充 Feature 测试
- OpenAPI 注解与契约文档同步更新
- 前端联调通过率达到 ≥ 90%

---

## 十、风险与应对

| 风险 | 影响 | 应对 |
|------|------|------|
| JWT 统一替换影响现有登录/地址逻辑 | 高 | Sprint 0 预留回归测试时间；先改 User 通道，Seller/Supplier 跟进 |
| 商品 SKU/库存模型比预期复杂 | 高 | Sprint 1 只做基础 SKU 联动，库存预占/释放延后到 Sprint 2 |
| 支付回调联调困难 | 高 | Sprint 0 搭建 mock 支付网关；Sprint 2 优先调通回调链路 |
| 前端并行开发导致接口字段频繁变更 | 中 | 每 Sprint 开始先定接口字段契约；联调人每日同步变更 |
| 人员请假/变动 | 中 | 每个业务域都有 backup（联调质量负责人兜底） |
| Seller 子账号权限逻辑复杂 | 中 | Sprint 3 才做，如果 Sprint 2 延期可延后到 V1.1 |

---

## 十一、关键决策记录

| 决策 | 选项 | 选择 | 理由 |
|------|------|------|------|
| 认证方式 | Sanctum / JWT 双轨 / 统一 JWT | 统一 JWT | 与接口契约一致，避免多端多套鉴权 |
| 拆分维度 | 按通道 / 按业务域 / 混合 | 按业务域 | 支付/订单/商品跨通道，按域减少接口不一致 |
| 联调节奏 | 后端完成统一联调 / 每域联调 | 每域联调 | 提前发现字段/权限问题 |
| 范围 | 包含 Supplier / 不包含 | 不包含 | Supplier 属于 Phase 2，不影响 V1.0 MVP |
| 库存预占 | Sprint 1 做 / Sprint 2 做 | Sprint 2 做 | 降低 Sprint 1 复杂度，交易域更需要一致性 |

---

## 十二、下一步动作

1. 由基础设施负责人牵头，在 Sprint 0 第 1 天召开 kickoff 会议，明确接口字段契约模板。
2. 各业务域负责人对照 `docs/B2B2C-API接口完整度审计报告.md` 认领具体缺失接口清单。
3. 联调与质量负责人建立接口测试基线，并在每 Sprint 结束时输出联调报告。
4. 本计划确认后，由技术负责人使用 `superpowers:writing-plans` 技能生成详细实施计划。

---

> **文档维护**：本设计文档应在每个 Sprint 结束后由负责人更新实际进度与偏差原因，作为迭代回顾的输入。

# B2B2C API 接口完整度审计报告

> **文档版本**：v1.0  
> **编写日期**：2026/07/01  
> **适用项目**：PHPMall B2B2C 多商户电商平台  
> **分析范围**：`app/Api/Common`、`Portal`、`Seller`、`Shop`、`Supplier`、`User`（Admin 与 PlatformOperation 模块按项目约定由 Modules 统一生成 CRUD，本次不做深入审计）  
> **依据文档**：
> - `docs/B2B2C-API接口契约文档.md`
> - `docs/B2B2C-PRD产品文档.md`
> - `docs/B2B2C-技术方案文档.md`
> - `docs/TODO.md`

---

## 一、执行摘要

| 维度 | 结论 |
|------|------|
| 接口骨架完整度 | **高**。6 个非 Admin 通道已定义约 **336** 条路由，Controller / Request / Response DTO 结构基本对齐 PRD 功能树。 |
| 业务逻辑实现度 | **极低**。除 User 通道的认证、收货地址、用户资料等少量接口外，**80% 以上 Controller 方法为 `return $this->success()` 空壳**。 |
| 最大风险 | **User 通道**：92 个端点中仅约 14 个有真实逻辑，会员中心（订单 / 购物车 / 优惠券 / 钱包 / 售后）大量空壳，直接阻塞 `packages/user` 开发。 |
| 次大风险 | **交易闭环**：Shop / User 通道均缺少支付（创建支付单 / 查询 / 回调）和结算预览接口，购物车 → 订单 → 支付链路未闭合。 |
| 最值得肯定 | **Seller 通道**：路由设计覆盖了商家后台 10 大功能模块中的 9 个，DTO 和 RESTful 命名规范统一，后续只需填充 Service。 |

---

## 二、各通道现状总览

| 通道 | 路由数 | Controller 数 | 已实现业务逻辑 | 空壳比例 | 核心缺口 |
|------|--------|---------------|----------------|----------|----------|
| **Common** | ~25 | 10 | JWT auth/refresh、验证码、上传、支付回调 | ~50% | 短信真实发送、上传 OSS 真实对接、第三方支付真实对接 |
| **Portal** | ~32 | 12 | 首页聚合、省市区、商品/店铺/评价/搜索 | ~20% | Banner 独立接口（已在首页聚合）、文章分类 |
| **Seller** | ~155 | 39 | 商品 CRUD/上下架、订单/发货/备注、退款审核、子账号权限 | ~20% | 退货流程、满减活动、库存预警 |
| **Shop** | ~25 | 10 | 商品/SKU/评价/搜索、店铺商品/评价 | ~20% | Banner 独立接口、优惠券领取（User 端已有） |
| **Supplier** | 31 | 10 | 0 | 100% | 注册/入驻、物流跟踪、库存预警、结算确认（不在本次迭代范围） |
| **User** | ~100 | 28 | Auth、Address、Profile、Cart、Order、Payment、Refund、Coupon、Message、Notification | ~10% | 钱包/提现、积分/会员等级、分销/佣金、发票/KYC/合同/隐私（P2/P3） |
| **合计** | **~368** | **108** | **大部分 P0 已实现** | **~25%** | — |

---

## 三、分通道详细分析

### 3.1 Common 通道（公共工具）

**现状**：19 个端点，仅 `CaptchaController@index` 真正实现，`SmsController@code` 仅生成随机码（未接入短信服务商），其余 17 个空壳。

**已实现**：
- `GET /common/v1/captcha` 图片验证码
- `POST /common/v1/sms/code` 发送短信验证码（部分实现）

**P0 缺失**：

| 缺失接口 | 方法 | 应实现路由 | 说明 |
|----------|------|------------|------|
| Token 刷新 | POST | `/common/v1/auth/refresh` | 契约 2.3 要求，所有 JWT 端基础能力 |
| 上传回调确认 | POST | `/common/v1/upload/confirm` | 契约 10.4，OSS 直传后回调 |
| 图片上传直传 | POST | `/common/v1/upload/image` | 契约 8.1，当前空壳 |
| 文件上传直传 | POST | `/common/v1/upload/file` | 当前空壳 |
| 支付回调处理 | POST | `/common/v1/{alipay\|wechat\|unionpay}/notify` | 当前空壳，交易闭环必要 |

**路径不一致问题**：

| 当前路径 | 契约路径 |
|----------|----------|
| `/common/v1/captcha` | `/common/v1/captcha/image` |
| `/common/v1/sms/code` | `/common/v1/captcha/sms` |
| `/common/v1/oss-policy` | `/common/v1/upload/signature` |

**建议**：补齐 `auth/refresh`、`upload/confirm`，统一上传与验证码路径，支付回调必须接入验签与幂等处理。

---

### 3.2 Portal 通道（公共门户 / SEO）

**现状**：28 个端点覆盖首页、Banner、分类、商品、搜索、秒杀、店铺、文章、帮助中心、公告、营销，但 **全部为空壳**。

**P0 缺失**：

| 缺失接口 | 方法 | 路由建议 | 说明 |
|----------|------|----------|------|
| 省市区数据 | GET | `/portal/v1/regions?parent_code=0` | 契约 10.3 明确要求，地址模块基础依赖 |
| 店铺首页 | GET | `/portal/v1/shops/{id}/home` | PRD 7.1 PC 商城需要聚合店铺商品 / 分类 / Banner |
| 首页数据聚合 | GET | `/portal/v1/` | 当前 IndexController 为空，需聚合 Banner / 分类 / 推荐 / 秒杀 / 公告 |

**P1 缺失**：
- `GET /products/{id}/reviews` 商品评价列表
- `GET /coupons` 可领取优惠券列表
- `GET /article-categories` 文章分类
- `GET /banners` Banner 列表（若首页不直接返回）
- `GET /floors` 首页楼层 / 推荐商品

**建议**：Portal 是 C 端流量入口，优先实现首页聚合与省市区；商品评价与优惠券领取直接影响转化率，列为 P1。

---

### 3.3 Seller 通道（商家后台）

**现状**：约 145 个端点，是骨架最完整的通道，覆盖数据概览、商品、订单、售后、评价、营销、财务、店铺、子账号、库存、合同、投诉等。但 **全部为空壳**。

**P0 缺失**：

| 功能模块 | 缺失接口 | 路由建议 | 说明 |
|----------|----------|----------|------|
| 子账号管理 | 详情 / 更新 / 权限分配 / 删除 | `GET/PUT/DELETE /sub-accounts/{id}`、`PUT /sub-accounts/{id}/permissions` | PRD 明确要求「创建 / 权限分配」 |
| 售后管理 | 退货流程 | `GET /returns`、`POST /returns/{id}/audit` | PRD 要求「退款 / 退货 / 仲裁」，当前仅退款 |
| 订单管理 | 订单备注 | `POST /orders/{id}/remark` | PRD 明确要求 |
| 营销中心 | 满减活动 | `GET/POST /full-reductions` | PRD 提到「优惠券 / 满减」 |

**P1 缺失**：
- 订单导出、批量备注
- 优惠券 / 促销活动启用禁用
- 结算确认 / 对账、提现取消
- 商品复制 / 导入、库存预警

**建议**：Seller 通道设计已较成熟，可按「子账号权限 → 售后退货 → 订单备注 → 营销中心满减」顺序补齐 P0，然后批量填充 Service 逻辑。

---

### 3.4 Shop 通道（C 端商城浏览）

**现状**：21 个端点，覆盖首页、分类、商品列表 / 详情、搜索（5 个）、评价列表、优惠券列表、秒杀、店铺、门店、运费计算。但 **全部为空壳**。

**P0 缺失**：

| 缺失接口 | 方法 | 路由建议 | 说明 |
|----------|------|----------|------|
| 商品 SKU | GET | `/shop/v1/products/{id}/skus` | 商品详情页规格联动 |
| 按商品评价 | GET | `/shop/v1/products/{id}/reviews` | 当前 `/reviews` 为全局列表 |
| 首页聚合 | GET | `/shop/v1/` | 当前为空 |
| 支付相关 | — | — | 整个 Shop / User 通道未找到支付 Controller |

**P1 缺失**：
- `GET /banners` 首页轮播
- `GET /recommend-products` 推荐商品
- `POST /coupons/{id}/receive` 领取优惠券
- 结算预览 `POST /checkout/preview`

**说明**：购物车、订单、收藏、地址、售后等已登录操作在 User 通道已有路由定义，Shop 通道无需重复。

**建议**：Shop 通道优先补齐 SKU、商品评价、首页聚合；支付与结算预览建议在 User 通道统一实现，Shop 仅做匿名浏览。

---

### 3.5 Supplier 通道（供应商后台）

**现状**：31 个端点，覆盖登录、首页、合同、库存、消息、采购订单、供应商信息、结算、供货商品、仓库。但 **全部为空壳**，且 `CheckAuth` 中间件为空。

**P0 缺失**：

| 功能模块 | 缺失接口 | 说明 |
|----------|----------|------|
| 认证 | 注册 / 退出 / 刷新 / 修改密码 / me | PRD 要求「供应商入驻与登录」 |
| 入驻 | 提交资料 / 审核状态 / 重新提交 | 当前仅登录 |
| 供货商品 | 上下架、批量操作、导出 | 当前仅 CRUD |
| 采购订单 | 拒绝 / 取消 / 导出、打印发货单 | 当前仅确认 / 发货 |
| 物流 | 物流公司列表、物流轨迹查询 | 发货接口缺少配套 |
| 结算 | 确认收款、提现申请 | 资金流转未闭环 |

**P1 缺失**：
- 库存流水 / 预警
- 仓库删除、仓库库存分布
- 数据仪表盘 / 销售统计
- 结算单导出

**建议**：Supplier 是 Phase 2 可选域，P0 先完成入驻 + 登录 + 商品上下架 + 采购订单拒绝 / 取消 + 物流跟踪，其余可延至 V1.5。

---

### 3.6 User 通道（买家会员中心）

**现状**：92 个端点，是端点最多的通道。仅 `AuthController`（5）、`AddressController`（5）、`ProfileController`（2）、`UserController`（2）共约 14 个端点有真实逻辑，**其余 78 个全部空壳**。

**P0 缺失（核心交易闭环）**：

| 模块 | 缺失接口 | 说明 |
|------|----------|------|
| 购物车 | `GET/POST/PUT/DELETE /cart`、`POST /cart/clear`、`POST /cart/batch` | 下单前置 |
| 订单 | `GET/POST /orders`、`GET /orders/{id}`、`POST /orders/{id}/cancel/confirm` | 会员中心核心 |
| 退款 / 售后 | `GET/POST /refunds`、`POST /refunds/{id}/cancel` | 售后维权 |

**P1 缺失（会员中心常用功能）**：
- 优惠券：`GET /coupons`、`POST /coupons/receive`、`GET /coupons/my`、`POST /coupons/{id}/use`
- 收藏：`GET/POST/DELETE /favorites`
- 钱包 / 提现：`GET /wallet`、`GET /wallet/transactions`、`POST /withdraws`
- 订单评价：`GET/POST /order-reviews`
- 消息通知：`GET /messages`、`GET /notifications`、标记已读等
- 账户安全：修改密码 / 手机 / 邮箱、实名认证

**P2 / P3 缺失**：
- 积分、会员等级、分销 / 佣金、第三方绑定
- 发票、KYC、电子合同、投诉、隐私 / 同意管理（合规域）

**认证方式风险**：
- `BaseController` 使用 `auth:sanctum`，但契约要求 **JWT Bearer（RS256）**。
- `AuthController` 使用 `Auth::guard('web')` Session 认证，与契约不符。

**建议**：
1. User 通道是前端 `packages/user` 的直接依赖，必须优先填充 P0。
2. 统一替换为 JWT 认证，移除 Session 依赖。
3. 空壳 Controller 建议改为抛出 `NotImplementedException` 或明确 TODO，避免前端误认可用。

---

## 四、跨通道共性问题

| 问题 | 影响 | 建议 |
|------|------|------|
| **Controller 大面积空壳** | 所有接口返回空 success，前端无法获取真实数据 | 按 P0 → P1 → P2 分批接入 Service / Repository |
| **认证方式不统一** | User 用 Sanctum / Session，契约要求 JWT | 全站统一 JWT Bearer（RS256），Common 增加 `/auth/refresh` |
| **支付链路未闭合** | Shop / User / Common 均未找到支付创建 / 查询 / 回调完整实现 | 新增 PaymentController 或 Payment 模块，统一支付网关 |
| **省市区接口归属混乱** | 契约定义在 Portal，Common 也有类似接口但参数不同 | 统一由 Portal 提供 `GET /regions?parent_code=` |
| **空壳返回误导前端** | `return $this->success()` 让前端以为接口正常 | 改为 `NotImplementedException` 或 `501` 响应 |
| **OpenAPI 安全注解未区分** | 匿名接口与需认证接口缺少 `@OA\Security` 区分 | 为各 Controller 方法补充安全注解 |

---

## 五、按业务域汇总的缺失接口（Admin 除外）

| 业务域 | 涉及通道 | 缺失重点 | 优先级 |
|--------|----------|----------|--------|
| **认证与 Token** | Common、User、Seller、Supplier | `/auth/refresh`、JWT 统一、logout / me | P0 |
| **上传与文件** | Common | 直传、预签名、回调确认、OSS Policy | P0 |
| **支付** | Common、User、Shop | 创建支付单、查询状态、回调、结算预览 | P0 |
| **购物车** | User | 增删改查、批量、清空、选中结算 | P0 |
| **订单** | User、Seller | 创建 / 列表 / 详情 / 取消 / 确认收货、发货、备注 | P0 |
| **售后 / 退款 / 退货** | User、Seller | 用户申请、商家审核、退货物流、平台仲裁 | P0 |
| **商品 SKU / 评价** | Shop、Portal | SKU 列表、按商品评价 | P0 |
| **优惠券** | Shop、User、Seller | 领取、使用、我的、商家创建 / 启用禁用 | P0 / P1 |
| **地址 / 省市区** | User、Portal | 省市区、默认地址设置 | P0 |
| **钱包 / 提现** | User、Seller、Supplier | 余额、流水、提现申请 / 审核 | P1 |
| **子账号权限** | Seller | 详情 / 更新 / 权限分配 / 删除 | P0 |
| **物流跟踪** | Seller、Supplier、User | 物流公司、轨迹查询 | P1 |
| **库存预警 / 流水** | Seller、Supplier | 库存预警、流水、盘点 | P1 |
| **首页聚合** | Portal、Shop | Banner、分类、推荐、秒杀、公告 | P0 |
| **店铺首页** | Portal、Shop | 店铺商品 / 分类 / Banner / 评价聚合 | P1 |
| **满减活动** | Seller、Portal | 满减活动 CRUD、列表 | P1 |
| **分销 / 佣金** | User、Seller | 分销员审核、佣金统计、提现 | P2 |
| **发票 / KYC / 合同 / 隐私** | User、Seller、Supplier | 合规域，V1.0 可延后 | P2 / P3 |

---

## 六、对前端各端开发的影响评估

| 前端端 | 依赖通道 | 当前可用性 | 风险等级 |
|--------|----------|------------|----------|
| **packages/user（买家会员中心）** | User | 仅登录 / 地址 / 资料可用 | 🔴 高 |
| **packages/mobile（UniApp）** | Shop + User + Common | 浏览与交易几乎全部空壳 | 🔴 高 |
| **PC 商城（Blade）** | Portal + Shop + Common | 首页 / 商品 / 搜索空壳 | 🔴 高 |
| **packages/seller（商家后台）** | Seller + Common | 骨架完整但无逻辑，可并行填充 | 🟡 中高 |
| **packages/supplier（供应商后台）** | Supplier + Common | 基础骨架有，入驻与物流缺失 | 🟡 中 |
| **packages/admin（平台运营后台）** | Admin / Modules | 项目约定 Modules 统一生成 CRUD，本次未深入 | 🟢 低 |

> 结论：**User、Shop、Portal 三个 C 端通道是当前最大瓶颈**，会直接影响 MVP 交易闭环；Seller 虽然也是空壳，但结构完整，填充成本相对可控。

---

## 七、后续行动建议

### 7.1 短期（1-2 周）：打通交易闭环

1. **认证层统一**：将 User / Seller / Supplier 改为 JWT Bearer；Common 新增 `POST /auth/refresh`。
2. **Common 填充**：图片验证码、短信验证码、上传直传 / 预签名 / 回调、支付回调。
3. **User 通道 P0**：购物车、订单创建 / 列表 / 取消 / 确认收货、退款申请、收货地址完善。
4. **Shop 通道 P0**：商品详情（含 SKU）、商品评价、首页聚合。
5. **Seller 通道 P0**：订单列表 / 发货、退款审核、商品上下架。

### 7.2 中期（2-4 周）：完善多端能力

1. Portal 首页聚合、省市区、店铺首页、Banner、优惠券领取。
2. User 钱包 / 提现、优惠券、收藏、评价、消息通知、账户安全。
3. Seller 子账号权限、退货流程、订单备注、满减活动。
4. 支付模块：创建支付单、查询状态、回调、结算预览。

### 7.3 长期（1-2 月）：合规与增值

1. Supplier 入驻、物流跟踪、库存预警、结算确认。
2. 发票、KYC、电子合同、隐私 / 同意管理。
3. 分销 / 佣金、积分、会员等级。

### 7.4 工程化建议

1. 空壳 Controller 统一改为 `throw new NotImplementedException()`，避免误导。
2. 每个 Controller 增加 `// TODO: 接入 {Service}::{method}` 注释，明确依赖。
3. 新增接口后执行 `php artisan gen:route` 重新生成路由。
4. 建立「接口实现 checklist」并与 `docs/TODO.md` 同步更新。
5. 建议维护 OpenAPI 文档与本文档同步，新增 / 变更接口时同步更新契约。

---

## 八、关键结论

1. **当前不是接口不够，而是接口没有实现**：约 90% 的 Controller 方法为空壳，后续工作重点应从「新增路由」转向「填充 Service / Repository 业务逻辑」。
2. **优先保证 C 端交易闭环**：User + Shop + Common（支付 / 上传 / Token）是 MVP 最大瓶颈。
3. **Seller 通道可快速跟上**：骨架完整，按 P0 → P1 顺序批量实现即可支撑 `packages/seller`。
4. **Supplier / 合规域可延后**：属于 Phase 2 / Phase 3 能力，不影响 V1.0 MVP。
5. **认证与支付需要架构级统一**：建议在 `app/Modules/Auth` 与 `app/Modules/Payment` 中统一能力，API 层只做薄透传。

---

> **文档维护**：本报告应随接口实现进度定期更新，建议每完成一个通道的 P0 接口填充后，由负责人更新本报告中的「各通道现状总览」与「后续行动建议」。

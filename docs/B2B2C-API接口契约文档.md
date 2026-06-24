# B2B2C 商城系统 API 接口契约文档

> **文档版本**：v1.0  
> **编写日期**：2026年6月  
> **适用项目**：PHP B2B2C 多商户电商平台  
> **目标读者**：前端开发工程师、后端开发工程师、测试工程师、第三方接入方

---

## 目录

1. [通用协议规范](#1-通用协议规范)
2. [认证与鉴权](#2-认证与鉴权)
3. [请求与响应规范](#3-请求与响应规范)
4. [状态码与错误码](#4-状态码与错误码)
5. [分页规范](#5-分页规范)
6. [幂等性设计](#6-幂等性设计)
7. [限流与熔断](#7-限流与熔断)
8. [文件上传规范](#8-文件上传规范)
9. [各端 API 分类说明](#9-各端-api-分类说明)
10. [公共工具 API（Common/Portal）](#10-公共工具-apicommonportal)
11. [数据类型定义](#11-数据类型定义)
12. [变更日志](#12-变更日志)

---

## 1. 通用协议规范

### 1.1 协议基础

| 项 | 规范 |
|----|------|
| 协议 | HTTPS only（生产环境强制 TLS 1.2+） |
| 数据格式 | JSON（`Content-Type: application/json`） |
| 字符编码 | UTF-8 |
| 时间格式 | ISO 8601（`2026-06-24T14:30:00+08:00`） |
| 日期格式 | `YYYY-MM-DD` |
| 金额格式 | 整数（单位：分），如 `19900` 表示 199.00 元 |
| 时区 | 默认东八区（Asia/Shanghai），必要时带 +08:00 偏移 |

### 1.2 请求方法语义

| 方法 | 语义 | 幂等性 | 示例 |
|------|------|--------|------|
| GET | 获取资源 | 是 | `GET /shop/v1/products` |
| POST | 创建资源 | 否 | `POST /shop/v1/orders` |
| PUT | 全量更新 | 是 | `PUT /user/v1/addresses/{id}` |
| PATCH | 部分更新 | 否 | `PATCH /seller/v1/products/{id}` |
| DELETE | 删除资源 | 是 | `DELETE /admin/v1/merchants/{id}` |

### 1.3 路由命名规范

```
/{端}/{版本}/{资源}[/子资源]/[{id}]/[动作]

端：admin | seller | shop | user | portal | common | supplier
版本：v1（当前）
资源：复数名词，小写 kebab-case
```

**示例**：
- `GET /shop/v1/products` — 商品列表
- `GET /shop/v1/products/{id}` — 商品详情
- `POST /shop/v1/orders/{id}/cancel` — 订单取消（动作）
- `POST /admin/v1/merchants/{id}/audit` — 商家审核（动作）
- `GET /seller/v1/orders?status=1&page=1&per_page=20` — 商家订单列表（查询参数）

---

## 2. 认证与鉴权

### 2.1 认证方式

| 端 | 认证方式 | Token 位置 | 说明 |
|----|---------|-----------|------|
| Admin | JWT Bearer | `Authorization: Bearer {token}` | 平台管理员登录后获取 |
| Seller | JWT Bearer | `Authorization: Bearer {token}` | 商家主账号/子账号登录后获取 |
| Shop | JWT Bearer | `Authorization: Bearer {token}` | 消费者登录后获取，未登录时部分接口可匿名访问 |
| User | JWT Bearer | `Authorization: Bearer {token}` | 同 Shop 端（用户中心） |
| Portal | 匿名 / JWT（可选） | 无 或 Bearer | 公共接口大部分可匿名访问 |
| Common | 混合 | 视接口而定 | 上传接口需签名或临时凭证 |

### 2.2 Token 规范

```http
Authorization: Bearer eyJhbGciOiJIUzI1NiIs...
```

**Token 结构**：JWT（RS256 非对称签名，公钥验签）

**Payload 字段**：
```json
{
  "sub": "10000001",
  "type": "user",
  "merchant_id": null,
  "jti": "uuid-unique-token-id",
  "iat": 1719216000,
  "exp": 1719226800,
  "refreshable_until": 1721808000
}
```

| 字段 | 说明 |
|------|------|
| `sub` | 用户ID（users.id / admins.id / merchant_staffs.id） |
| `type` | 账号类型 `user` / `admin` / `merchant_staff` |
| `merchant_id` | 商家ID（仅商家子账号携带，用于数据隔离） |
| `jti` | Token 唯一标识，用于黑名单/刷新追踪 |
| `iat` | 签发时间 |
| `exp` | 访问 Token 过期时间（默认 2 小时） |
| `refreshable_until` | 可刷新截止时间（默认 30 天） |

### 2.3 Token 刷新机制

```
POST /common/v1/auth/refresh
Authorization: Bearer {即将过期的 access_token}

Request:
{}

Response:
{
  "code": 0,
  "data": {
    "access_token": "new-access-token",
    "expires_in": 7200,
    "refresh_token": "new-refresh-token"
  }
}
```

**规则**：
- Access Token 有效期 2 小时
- Refresh Token 有效期 30 天（可刷新窗口）
- 刷新时验证 `jti` 未在黑名单中（Redis 存储）
- 支持单设备登录或多设备登录（根据业务配置）

### 2.4 多设备登录控制

| 策略 | 说明 |
|------|------|
| 单设备 | 新登录踢掉旧 Token（旧 Token 加入黑名单） |
| 多设备（默认） | 每个设备独立 Token，最多 N 个设备同时在线 |
| 设备绑定 | 敏感操作（支付、提现）需验证设备指纹 |

---

## 3. 请求与响应规范

### 3.1 请求通用头

```http
GET /shop/v1/products?page=1&per_page=20 HTTP/1.1
Host: api.phpmall.com
Accept: application/json
Authorization: Bearer {token}
X-Request-Id: req-uuid-123456
X-Client-Version: pc-mall/1.2.0
X-Device-Id: device-uuid-abc123
```

### 3.2 响应通用结构

**成功响应（HTTP 200）**：
```json
{
  "code": 0,
  "message": "success",
  "data": { ... },
  "request_id": "req-uuid-123456",
  "timestamp": "2026-06-24T14:30:00+08:00"
}
```

**列表响应（HTTP 200）**：
```json
{
  "code": 0,
  "message": "success",
  "data": {
    "items": [ ... ],
    "pagination": {
      "page": 1,
      "per_page": 20,
      "total": 156,
      "total_pages": 8,
      "has_next": true,
      "has_prev": false
    }
  },
  "request_id": "req-uuid-123456"
}
```

**错误响应（HTTP 4xx/5xx）**：
```json
{
  "code": 1001,
  "message": "参数校验失败",
  "errors": {
    "phone": ["手机号格式不正确"],
    "password": ["密码长度不能少于8位"]
  },
  "request_id": "req-uuid-123456"
}
```

| 字段 | 类型 | 必返 | 说明 |
|------|------|------|------|
| `code` | int | 是 | 业务状态码，0=成功，非0=错误 |
| `message` | string | 是 | 人类可读的错误描述 |
| `data` | any | 否 | 成功时返回数据 |
| `errors` | object | 否 | 字段级错误（校验失败时） |
| `request_id` | string | 是 | 请求追踪ID，用于日志排查 |
| `timestamp` | string | 否 | 服务器响应时间 |

### 3.3 空值处理

| 场景 | 处理方式 | 示例 |
|------|----------|------|
| 字段无值 | 返回 `null`（不返回空字符串或 0） | `"avatar_url": null` |
| 空列表 | 返回 `[]` | `"items": []` |
| 空对象 | 返回 `null` | `"merchant": null` |
| 布尔值 | 使用 `true`/`false` | `"is_default": true` |
| 删除字段 | 软删除的关联数据，字段返回 `null` | 已删除的商家返回 `null` |

---

## 4. 状态码与错误码

### 4.1 HTTP 状态码

| HTTP 码 | 使用场景 |
|---------|---------|
| 200 | 成功（GET/POST/PUT/PATCH/DELETE） |
| 201 | 创建成功（POST 创建资源） |
| 204 | 删除成功，无返回体（DELETE） |
| 400 | 请求参数错误（校验失败、格式错误） |
| 401 | 未认证（Token 缺失、过期、无效） |
| 403 | 无权限（已认证但无权访问该资源） |
| 404 | 资源不存在 |
| 409 | 资源冲突（重复创建、状态冲突） |
| 422 | 业务校验失败（如库存不足、优惠券已过期） |
| 429 | 请求过于频繁（限流触发） |
| 500 | 服务器内部错误 |
| 503 | 服务暂时不可用（维护中、熔断） |

### 4.2 业务错误码（code 字段）

| 错误码 | 含义 | HTTP 码 | 常见场景 |
|--------|------|---------|---------|
| 0 | 成功 | 200 | — |
| 1001 | 参数校验失败 | 400 | 字段缺失、格式错误、类型错误 |
| 1002 | 请求参数格式错误 | 400 | JSON 解析失败、非法字符 |
| 1003 | 分页参数错误 | 400 | page < 1, per_page > 100 |
| 2001 | 未认证 | 401 | Token 缺失、过期、无效 |
| 2002 | 登录已失效 | 401 | Token 被刷新、被踢出 |
| 2003 | 认证方式不支持 | 401 | 错误的 Authorization 头格式 |
| 3001 | 无权限访问 | 403 | 角色无该接口权限 |
| 3002 | 数据越权访问 | 403 | 访问了其他商家的数据 |
| 3003 | 账号已禁用 | 403 | 用户/商家/管理员被冻结 |
| 4001 | 资源不存在 | 404 | 商品已删除、订单不存在 |
| 4002 | 接口不存在 | 404 | 404 Not Found |
| 5001 | 业务状态冲突 | 409 | 订单已支付，不能重复支付 |
| 5002 | 资源重复 | 409 | 手机号已注册、SKU 编码重复 |
| 6001 | 库存不足 | 422 | 下单时库存不足 |
| 6002 | 优惠券不可用 | 422 | 已过期、已使用、不满足门槛 |
| 6003 | 余额不足 | 422 | 钱包余额不足 |
| 6004 | 商家状态异常 | 422 | 商家未审核、已冻结 |
| 6005 | 支付失败 | 422 | 第三方支付返回失败 |
| 6006 | 退款金额超限 | 422 | 退款金额大于实付金额 |
| 6007 | 秒杀已售罄 | 422 | 秒杀商品库存不足 |
| 7001 | 请求过于频繁 | 429 | 限流触发 |
| 7002 | 验证码错误次数过多 | 429 | 短信验证码连续错误 |
| 8001 | 图片上传失败 | 400 | 文件过大、格式不支持 |
| 9001 | 服务器内部错误 | 500 | 未捕获异常 |
| 9002 | 服务暂时不可用 | 503 | 维护中、熔断 |

### 4.3 错误码使用示例

```json
// 401 未认证
{
  "code": 2001,
  "message": "未认证或登录已过期，请重新登录",
  "request_id": "req-abc123"
}

// 403 无权限
{
  "code": 3002,
  "message": "无权访问该商家的数据",
  "request_id": "req-abc123"
}

// 422 业务校验失败
{
  "code": 6001,
  "message": "库存不足",
  "data": {
    "sku_id": 10025,
    "requested": 5,
    "available": 2
  },
  "request_id": "req-abc123"
}

// 429 限流
{
  "code": 7001,
  "message": "请求过于频繁，请稍后重试",
  "data": {
    "retry_after": 60,
    "limit": 100,
    "window": 60
  },
  "request_id": "req-abc123"
}
```

---

## 5. 分页规范

### 5.1 请求参数

| 参数 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| `page` | int | 否 | 1 | 页码，从 1 开始 |
| `per_page` | int | 否 | 20 | 每页数量，上限 100 |
| `sort_by` | string | 否 | `created_at` | 排序字段（白名单） |
| `sort_direction` | string | 否 | `desc` | `asc` 或 `desc` |

### 5.2 响应结构

```json
{
  "items": [ ... ],
  "pagination": {
    "page": 1,
    "per_page": 20,
    "total": 156,
    "total_pages": 8,
    "has_next": true,
    "has_prev": false
  }
}
```

### 5.3 游标分页（大数据量场景）

当数据量超过 10 万条时，支持游标分页：

```http
GET /admin/v1/orders?cursor=eyJpZCI6MTAwMDAsImNyZWF0ZWRfYXQiOiIyMDI2LTA2LTI0IDE0OjMwOjAwIn0=&per_page=50
```

**参数**：
- `cursor`：Base64 编码的游标（如 `{"id":10000,"created_at":"2026-06-24 14:30:00"}`）
- `per_page`：同上

**响应**：
```json
{
  "items": [ ... ],
  "pagination": {
    "next_cursor": "eyJpZCI6MTAwNTB9",
    "has_next": true,
    "per_page": 50
  }
}
```

---

## 6. 幂等性设计

### 6.1 幂等键（Idempotency-Key）

对于非幂等操作（创建订单、支付、退款、提现、审核），客户端必须携带幂等键：

```http
POST /shop/v1/orders
Idempotency-Key: 550e8400-e29b-41d4-a716-446655440000

{
  "cart_ids": [1, 2, 3],
  "address_id": 5,
  "coupon_id": null
}
```

**规则**：
- 幂等键由客户端生成（UUID），同一业务操作复用同一键
- 服务端缓存幂等键结果（Redis，TTL 24 小时）
- 重复请求返回首次执行结果，不重复执行业务逻辑
- 幂等键作用范围：用户级别（不同用户可复用同一键）

### 6.2 幂等接口清单

| 接口 | 方法 | 幂等键 | 说明 |
|------|------|--------|------|
| 创建订单 | POST | 必须 | 防止重复下单 |
| 支付订单 | POST | 必须 | 防止重复支付 |
| 取消订单 | POST | 建议 | 防止重复取消 |
| 申请退款 | POST | 必须 | 防止重复申请 |
| 提现申请 | POST | 必须 | 防止重复提现 |
| 商家审核 | POST | 建议 | 防止重复审核 |
| 优惠券领取 | POST | 必须 | 防止重复领取 |
| 确认收货 | POST | 建议 | 防止重复确认 |

---

## 7. 限流与熔断

### 7.1 限流策略

| 粒度 | 策略 | 限制 | 响应 |
|------|------|------|------|
| 全局 IP | 匿名请求 | 60 次/分钟 | 429 + `retry_after` |
| 用户 Token | 已认证请求 | 300 次/分钟 | 429 |
| 商家 Staff | 商家后台 | 600 次/分钟 | 429 |
| 管理员 | 管理后台 | 1200 次/分钟 | 429 |
| 特殊接口 | 短信验证码 | 1 次/60 秒 | 429 |
| 特殊接口 | 登录 | 5 次/分钟 | 429 + 验证码 |
| 特殊接口 | 支付 | 10 次/分钟 | 429 |

**响应头**：
```http
X-RateLimit-Limit: 300
X-RateLimit-Remaining: 289
X-RateLimit-Reset: 1719226800
```

### 7.2 熔断策略

- 第三方支付接口（微信/支付宝）失败率超过 50% 时，触发熔断，5 分钟内返回 `503`
- 熔断期间自动切换备用渠道（如有）
- 熔断恢复后，逐步放量（10% -> 50% -> 100%）

---

## 8. 文件上传规范

### 8.1 上传方式

**方式一：直传服务端（小文件 < 5MB）**
```http
POST /common/v1/upload/image
Content-Type: multipart/form-data

file: [二进制文件]
type: product | avatar | merchant_license | banner | review
```

**方式二：服务端预签名 URL（大文件 / 推荐）**
```http
POST /common/v1/upload/signature
Content-Type: application/json

{
  "file_name": "product_123.jpg",
  "file_size": 2048000,
  "mime_type": "image/jpeg",
  "type": "product"
}

Response:
{
  "code": 0,
  "data": {
    "upload_url": "https://oss.aliyuncs.com/...?signature=xxx",
    "access_url": "https://cdn.phpmall.com/product/2026/06/abc123.jpg",
    "expires_in": 300
  }
}
```

客户端直传 OSS，成功后回调服务端确认。

### 8.2 文件限制

| 类型 | 格式 | 大小 | 尺寸限制 |
|------|------|------|---------|
| 商品图片 | jpg, png, webp | <= 5MB | 建议 800x800 |
| 商品详情图 | jpg, png | <= 10MB | 宽度 <= 1200px |
| 头像 | jpg, png | <= 2MB | 建议 200x200 |
| 资质证件 | jpg, png, pdf | <= 10MB | — |
| 评价图片 | jpg, png | <= 5MB | — |
| Banner | jpg, png | <= 10MB | 建议 1920x600 |

### 8.3 图片处理参数

```
// 获取缩略图（CDN 自动处理）
https://cdn.phpmall.com/product/abc.jpg?w=300&h=300&fit=cover&format=webp

// 参数说明
w=300        // 宽度
h=300        // 高度
fit=cover    // cover / contain / fill
format=webp  // 输出格式
quality=80   // 质量 1-100
```

---

## 9. 各端 API 分类说明

### 9.1 端前缀与认证要求

| 端 | 路由前缀 | 认证要求 | 主要使用方 |
|----|---------|---------|-----------|
| Admin | `/admin/v1/` | 必须（JWT，admin 类型） | 平台运营后台 |
| Seller | `/seller/v1/` | 必须（JWT，merchant_staff 类型） | 商家后台 |
| Shop | `/shop/v1/` | 部分可选（浏览可匿名，操作需登录） | PC 商城、H5 商城 |
| User | `/user/v1/` | 必须（JWT，user 类型） | 用户个人中心 |
| Portal | `/portal/v1/` | 匿名 / 可选 | 公共门户、SEO |
| Common | `/common/v1/` | 混合 | 公共工具（上传、验证码、省市区） |
| Supplier | `/supplier/v1/` | 必须 | 供应商端（预留） |

### 9.2 跨端接口复用原则

- 同一资源在不同端返回不同字段 -> 使用 Resource 层控制，而非单独接口
- 同一操作在不同端有不同权限 -> 中间件控制，Controller 复用 Service
- 列表查询在不同端有不同排序/过滤 -> 通过查询参数控制，共用 Service 逻辑

---

## 10. 公共工具 API（Common/Portal）

### 10.1 短信验证码

```http
POST /common/v1/captcha/sms

Request:
{
  "phone": "13800138000",
  "type": "register"  // register | login | reset_password | bind
}

Response:
{
  "code": 0,
  "message": "验证码已发送",
  "data": {
    "retry_after": 60  // 再次发送间隔
  }
}
```

**限制**：
- 同一手机号 60 秒内只能发送 1 次
- 同一手机号 24 小时内最多 10 次
- 验证码有效期 5 分钟
- 错误输入 5 次后失效

### 10.2 图片验证码（防刷）

```http
GET /common/v1/captcha/image

Response:
{
  "code": 0,
  "data": {
    "captcha_key": "uuid-key",
    "captcha_image": "base64://..."
  }
}
```

### 10.3 省市区数据

```http
GET /portal/v1/regions?parent_code=0

Response:
{
  "code": 0,
  "data": [
    {
      "code": "110000",
      "name": "北京市",
      "level": 1,
      "children": null
    }
  ]
}
```

### 10.4 上传回调确认

```http
POST /common/v1/upload/confirm

Request:
{
  "type": "product",
  "file_name": "abc123.jpg",
  "file_size": 2048000,
  "mime_type": "image/jpeg"
}

Response:
{
  "code": 0,
  "data": {
    "url": "https://cdn.phpmall.com/product/2026/06/abc123.jpg"
  }
}
```

---

## 11. 数据类型定义

### 11.1 通用类型

```typescript
// 分页元数据
interface PaginationMeta {
  page: number;
  per_page: number;
  total: number;
  total_pages: number;
  has_next: boolean;
  has_prev: boolean;
}

// 游标分页元数据
interface CursorPaginationMeta {
  next_cursor: string | null;
  has_next: boolean;
  per_page: number;
}

// 通用响应
interface ApiResponse<T> {
  code: number;
  message: string;
  data: T;
  request_id: string;
  timestamp?: string;
}

// 列表响应
interface ListResponse<T> {
  items: T[];
  pagination: PaginationMeta | CursorPaginationMeta;
}

// 字段错误
interface FieldError {
  [field: string]: string[];
}
```

### 11.2 核心资源类型（TypeScript 示意）

```typescript
interface Product {
  id: string;
  title: string;
  subtitle: string | null;
  price: number;        // 分
  market_price: number; // 分
  cover: string;
  images: string[];
  sales_count: number;
  stock: number;
  status: 0 | 1;
  merchant: {
    id: number;
    name: string;
  } | null;
  skus: ProductSku[];
  created_at: string;
}

interface Order {
  id: string;
  order_no: string;
  status: number;
  pay_status: number;
  product_amount: number;
  discount_amount: number;
  freight_amount: number;
  pay_amount: number;
  pay_method: number | null;
  pay_time: string | null;
  items: OrderItem[];
  shipment: OrderShipment | null;
  created_at: string;
}
```

---

## 12. 变更日志

| 版本 | 日期 | 变更内容 | 作者 |
|------|------|---------|------|
| v1.0 | 2026-06-24 | 初始版本，定义通用协议、认证、分页、错误码、幂等性 | 架构组 |

> **文档结束**  
> 本接口契约应随 API 迭代持续更新，新增/变更接口需同步维护 OpenAPI Schema 和本文档。

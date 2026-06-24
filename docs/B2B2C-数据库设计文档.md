# B2B2C 商城系统数据库设计文档

> **文档版本**：v1.0  
> **编写日期**：2026年6月  
> **适用项目**：PHP B2B2C 多商户电商平台  
> **目标读者**：后端开发工程师、DBA、架构师

---

## 目录

1. [设计原则与规范](#1-设计原则与规范)
2. [命名规范](#2-命名规范)
3. [核心模块 ER 关系概述](#3-核心模块-er-关系概述)
4. [用户与权限模块](#4-用户与权限模块)
5. [商家与店铺模块](#5-商家与店铺模块)
6. [商品与库存模块](#6-商品与库存模块)
7. [订单与售后模块](#7-订单与售后模块)
8. [支付与财务模块](#8-支付与财务模块)
9. [营销与分销模块](#9-营销与分销模块)
10. [内容管理模块](#10-内容管理模块)
11. [系统配置与日志模块](#11-系统配置与日志模块)
12. [索引策略](#12-索引策略)
13. [分库分表策略](#13-分库分表策略)
14. [数据字典](#14-数据字典)

---

## 1. 设计原则与规范

### 1.1 设计原则

| 原则 | 说明 |
|------|------|
| **第三范式为主** | 核心实体表遵循 3NF，减少冗余；报表/统计表允许适当冗余 |
| **软删除优先** | 所有业务表使用 `deleted_at`（NULLABLE TIMESTAMP）实现软删除，保留审计轨迹 |
| **租户字段隔离** | 多商户表统一包含 `merchant_id`（可为 NULL 表示平台级数据），配合全局 Scope 过滤 |
| **金额统一使用整数分** | 所有金额字段（price, amount, fee）使用 `BIGINT UNSIGNED` 存储「分」，避免浮点精度问题 |
| **状态使用 TINYINT UNSIGNED** | 状态字段使用无符号 tinyint，配合 PHP 8.4 Backed Enum 语义化 |
| **时间戳统一** | 所有表包含 `created_at` + `updated_at`（`TIMESTAMP`），业务时间（如下单时间）使用独立字段 |
| **JSON 字段适度使用** | 变长/非结构化数据（如商品规格参数、扩展属性）使用 `JSON` 类型，避免过度拆分 |
| **乐观锁** | 高并发更新表（库存、钱包余额）使用 `version`（`INT UNSIGNED`）乐观锁 |

### 1.2 字符集与排序规则

```sql
-- 数据库级
CREATE DATABASE phpmall CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 表级默认
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 2. 命名规范

| 对象 | 命名规则 | 示例 |
|------|----------|------|
| 表名 | 复数形式，小写下划线 | `users`, `order_items`, `merchant_settlements` |
| 字段名 | 小写下划线 | `phone_verified_at`, `merchant_id` |
| 主键 | 表名单数 + `_id` | `user_id`, `order_id` |
| 外键 | 关联表名 + `_id` | `merchant_id`, `product_id` |
| 索引 | `idx_` + 字段名 或 `uniq_` + 字段名 | `idx_merchant_status`, `uniq_phone` |
| 分区表 | 原表名 + `_` + 时间后缀 | `orders_202601`, `orders_202602` |

---

## 3. 核心模块 ER 关系概述

```
                    +-----------+         +-----------+
                    |  users    |<>-------<| addresses |
                    +----+------+         +-----------+
                         | 1
                         |
                         | N
                    +----v------+
                    |  orders   |<>-------<| order_items |
                    +----+------+         +-----------+
                         | 1
                         | N
         +---------------+---------------+
         |               |               |
    +----v----+     +----v----+     +----v------+
    |payments |     |refunds  |     |shipments  |
    +---------+     +---------+     +-----------+

    +-----------+         +-----------+         +-----------+
    | merchants |<>------<| products  |<>------<|product_skus|
    +----+------+         +-----------+         +-----------+
         | 1                     | N
         |                       |
         | N                     | N
    +----v------+           +----v------+
    |merchant_  |           | product_   |
    |staffs     |           | categories |
    +-----------+           +-----------+
```

---

## 4. 用户与权限模块

### 4.1 users（用户表）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | 用户ID |
| phone | `VARCHAR(20)` | NOT NULL, UNIQUE | 手机号（登录主键） |
| email | `VARCHAR(255)` | NULL, UNIQUE | 邮箱 |
| password_hash | `VARCHAR(255)` | NOT NULL | bcrypt 密码哈希 |
| nickname | `VARCHAR(100)` | NOT NULL | 昵称 |
| avatar_url | `VARCHAR(500)` | NULL | 头像 URL |
| real_name | `VARCHAR(50)` | NULL | 真实姓名 |
| id_card_no | `VARCHAR(18)` | NULL | 身份证号（AES 加密） |
| id_card_verified | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 实名认证状态 0=未认证 1=审核中 2=已认证 3=认证失败 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 状态 0=禁用 1=正常 |
| last_login_at | `TIMESTAMP` | NULL | 最后登录时间 |
| last_login_ip | `VARCHAR(45)` | NULL | 最后登录 IP |
| source | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 注册来源 1=手机号 2=微信 3=支付宝 |
| created_at | `TIMESTAMP` | NOT NULL DEFAULT CURRENT_TIMESTAMP | |
| updated_at | `TIMESTAMP` | NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | |
| deleted_at | `TIMESTAMP` | NULL | 软删除 |

**索引**：`idx_phone(phone)`, `idx_status_created(status,created_at)`, `idx_source(source)`

### 4.2 user_wallets（用户钱包）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| user_id | `BIGINT UNSIGNED` | NOT NULL, UNIQUE | 关联 users |
| balance | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 余额（分） |
| frozen_balance | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 冻结金额（分） |
| total_recharge | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 累计充值（分） |
| total_consumption | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 累计消费（分） |
| version | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 乐观锁 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_user_id(user_id)`

### 4.3 wallet_transactions（钱包流水）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| user_id | `BIGINT UNSIGNED` | NOT NULL | |
| type | `TINYINT UNSIGNED` | NOT NULL | 1=充值 2=消费 3=退款 4=提现 5=佣金 |
| amount | `BIGINT` | NOT NULL | 变动金额（分，正负） |
| balance_before | `BIGINT UNSIGNED` | NOT NULL | 变动前余额 |
| balance_after | `BIGINT UNSIGNED` | NOT NULL | 变动后余额 |
| relation_type | `VARCHAR(50)` | NOT NULL | 关联模型类型 `Order`, `Withdraw` |
| relation_id | `BIGINT UNSIGNED` | NOT NULL | 关联模型ID |
| remark | `VARCHAR(255)` | NULL | 备注 |
| created_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_user_type(user_id,type)`, `idx_relation(relation_type,relation_id)`

### 4.4 user_addresses（收货地址）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| user_id | `BIGINT UNSIGNED` | NOT NULL | |
| receiver_name | `VARCHAR(50)` | NOT NULL | 收货人 |
| receiver_phone | `VARCHAR(20)` | NOT NULL | 收货电话 |
| province_code | `VARCHAR(20)` | NOT NULL | 省编码 |
| city_code | `VARCHAR(20)` | NOT NULL | 市编码 |
| district_code | `VARCHAR(20)` | NOT NULL | 区编码 |
| detail | `VARCHAR(255)` | NOT NULL | 详细地址 |
| is_default | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 是否默认 0=否 1=是 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_user_id(user_id)`, `idx_user_default(user_id,is_default)`

### 4.5 admins（平台管理员）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| username | `VARCHAR(50)` | NOT NULL, UNIQUE | 登录名 |
| password_hash | `VARCHAR(255)` | NOT NULL | bcrypt |
| real_name | `VARCHAR(50)` | NULL | 姓名 |
| avatar_url | `VARCHAR(500)` | NULL | 头像 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 0=禁用 1=正常 |
| last_login_at | `TIMESTAMP` | NULL | |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

### 4.6 roles & permissions & model_has_roles & model_has_permissions（RBAC，使用 spatie/laravel-permission 标准表）

沿用 `spatie/laravel-permission` 默认表结构，增加 `merchant_id` 到 `roles` 表支持商家子账号角色隔离。

| 表 | 说明 |
|----|------|
| `roles` | 角色定义，增加 `merchant_id` NULLABLE（NULL 表示平台角色） |
| `permissions` | 权限定义（如 `product:create`, `order:view`） |
| `model_has_roles` | 多态关联（admin、user、merchant_staff） |
| `model_has_permissions` | 多态直接权限 |
| `role_has_permissions` | 角色-权限映射 |

---

## 5. 商家与店铺模块

### 5.1 merchants（商家表）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | 商家ID |
| name | `VARCHAR(100)` | NOT NULL | 店铺名称 |
| logo_url | `VARCHAR(500)` | NULL | 店铺Logo |
| cover_url | `VARCHAR(500)` | NULL | 店铺封面 |
| description | `TEXT` | NULL | 店铺简介 |
| contact_phone | `VARCHAR(20)` | NOT NULL | 联系手机 |
| contact_name | `VARCHAR(50)` | NOT NULL | 联系人 |
| business_license_no | `VARCHAR(50)` | NULL | 营业执照号 |
| business_license_url | `VARCHAR(500)` | NULL | 营业执照图片 |
| legal_person_name | `VARCHAR(50)` | NULL | 法人姓名 |
| legal_person_id_card | `VARCHAR(18)` | NULL | 法人身份证（AES） |
| settlement_cycle | `TINYINT UNSIGNED` | NOT NULL DEFAULT 7 | 结算周期 T+N 天 |
| settlement_rate | `DECIMAL(5,4)` | NOT NULL DEFAULT 0.0500 | 平台抽成比例（5%） |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待审核 1=正常 2=冻结 3=关闭 |
| audit_status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待审核 1=通过 2=拒绝 |
| audit_remark | `VARCHAR(500)` | NULL | 审核备注 |
| frozen_reason | `VARCHAR(500)` | NULL | 冻结原因 |
| frozen_until | `TIMESTAMP` | NULL | 冻结截止时间 |
| total_sales_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 累计销售额（分） |
| total_order_count | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 累计订单数 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |
| deleted_at | `TIMESTAMP` | NULL | |

**索引**：`idx_status(status)`, `idx_audit(audit_status)`, `idx_created(created_at)`

### 5.2 merchant_staffs（商家子账号）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| merchant_id | `INT UNSIGNED` | NOT NULL | |
| username | `VARCHAR(50)` | NOT NULL | 登录名 |
| password_hash | `VARCHAR(255)` | NOT NULL | |
| real_name | `VARCHAR(50)` | NULL | |
| phone | `VARCHAR(20)` | NULL | |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | |
| last_login_at | `TIMESTAMP` | NULL | |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_merchant(merchant_id)`

### 5.3 merchant_settlements（商家结算单）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| merchant_id | `INT UNSIGNED` | NOT NULL | |
| settlement_no | `VARCHAR(32)` | NOT NULL, UNIQUE | 结算单号 |
| start_date | `DATE` | NOT NULL | 结算周期开始 |
| end_date | `DATE` | NOT NULL | 结算周期结束 |
| total_order_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 订单总额 |
| platform_fee | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 平台佣金 |
| merchant_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 商家实得 |
| refund_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 退款金额 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待确认 1=已确认 2=已结算 |
| confirmed_at | `TIMESTAMP` | NULL | 商家确认时间 |
| settled_at | `TIMESTAMP` | NULL | 平台结算时间 |
| created_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_merchant_status(merchant_id,status)`, `idx_settlement_no(settlement_no)`

### 5.4 merchant_withdraws（商家提现申请）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| merchant_id | `INT UNSIGNED` | NOT NULL | |
| amount | `BIGINT UNSIGNED` | NOT NULL | 提现金额（分） |
| account_type | `TINYINT UNSIGNED` | NOT NULL | 1=银行卡 2=支付宝 3=微信 |
| account_name | `VARCHAR(50)` | NOT NULL | 收款人姓名 |
| account_no | `VARCHAR(100)` | NOT NULL | 收款账号（AES） |
| bank_name | `VARCHAR(100)` | NULL | 银行名称 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待审核 1=审核通过 2=审核拒绝 3=已转账 4=转账失败 |
| audit_remark | `VARCHAR(255)` | NULL | 审核备注 |
| audited_at | `TIMESTAMP` | NULL | |
| transferred_at | `TIMESTAMP` | NULL | |
| created_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_merchant_status(merchant_id,status)`

---

## 6. 商品与库存模块

### 6.1 product_categories（商品分类）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| parent_id | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 父分类ID，0=根 |
| name | `VARCHAR(50)` | NOT NULL | 分类名称 |
| icon_url | `VARCHAR(500)` | NULL | 图标 |
| sort_order | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 排序 |
| is_show | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 是否显示 |
| level | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 层级 1/2/3 |
| path | `VARCHAR(255)` | NOT NULL | 层级路径 `1,5,23` |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_parent(parent_id)`, `idx_show_sort(is_show,sort_order)`

### 6.2 products（商品 SPU 表）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | 商品ID |
| merchant_id | `INT UNSIGNED` | NOT NULL | 商家ID |
| category_id | `INT UNSIGNED` | NOT NULL | 分类ID |
| title | `VARCHAR(200)` | NOT NULL | 商品标题 |
| subtitle | `VARCHAR(255)` | NULL | 副标题 |
| description | `LONGTEXT` | NULL | 富文本详情 |
| main_image | `VARCHAR(500)` | NOT NULL | 主图 |
| images | `JSON` | NOT NULL | 相册 `["url1","url2"]` |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=下架 1=上架 |
| audit_status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待审核 1=通过 2=拒绝 |
| audit_remark | `VARCHAR(500)` | NULL | 审核备注 |
| min_price | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 最低售价（分），用于列表展示 |
| max_price | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 最高售价（分），划线价 |
| cost_price | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 成本价（分，仅商家可见） |
| sales_count | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 销量（可异步统计） |
| stock_type | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 1=统一库存 2=SKU独立库存 |
| total_stock | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 总库存（冗余，加速查询） |
| weight | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 重量（克） |
| freight_template_id | `INT UNSIGNED` | NULL | 运费模板ID |
| attributes | `JSON` | NULL | 规格属性定义 `[{"name":"颜色","values":["红","蓝"]}]` |
| seo_title | `VARCHAR(200)` | NULL | SEO标题 |
| seo_keywords | `VARCHAR(255)` | NULL | SEO关键词 |
| seo_description | `VARCHAR(500)` | NULL | SEO描述 |
| is_hot | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 是否热销 |
| is_new | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 是否新品 |
| is_recommend | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 是否推荐 |
| sort_order | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 排序权重 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |
| deleted_at | `TIMESTAMP` | NULL | |

**索引**：`idx_merchant_status(merchant_id,status)`, `idx_category_status(category_id,status,audit_status)`, `idx_audit(audit_status)`, `idx_sort(sort_order,created_at)`, `idx_search(title)`（FULLTEXT 或 `idx_title` + 前缀）

### 6.3 product_skus（商品 SKU 表）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| product_id | `BIGINT UNSIGNED` | NOT NULL | 商品ID |
| merchant_id | `INT UNSIGNED` | NOT NULL | 冗余，方便商家端查询 |
| sku_code | `VARCHAR(100)` | NOT NULL | SKU编码（商家自定义或自动生成） |
| sku_specs | `JSON` | NOT NULL | 规格组合 `{"颜色":"红","尺寸":"XL"}` |
| price | `BIGINT UNSIGNED` | NOT NULL | 售价（分） |
| market_price | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 市场价（分） |
| cost_price | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 成本价（分） |
| stock | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 库存 |
| stock_alarm | `INT UNSIGNED` | NOT NULL DEFAULT 10 | 库存预警值 |
| weight | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 重量（克） |
| image | `VARCHAR(500)` | NULL | SKU独立图片 |
| sales_count | `INT UNSIGNED` | NOT NULL DEFAULT 0 | SKU销量 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 0=禁用 1=启用 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_product(product_id)`, `idx_merchant(merchant_id)`, `idx_status(status)`

### 6.4 carts（购物车）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| user_id | `BIGINT UNSIGNED` | NOT NULL | |
| merchant_id | `INT UNSIGNED` | NOT NULL | 冗余 |
| product_id | `BIGINT UNSIGNED` | NOT NULL | |
| sku_id | `BIGINT UNSIGNED` | NOT NULL | |
| quantity | `INT UNSIGNED` | NOT NULL DEFAULT 1 | 数量 |
| is_selected | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 是否选中 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_user(user_id)`, `idx_user_merchant(user_id,merchant_id)`, `UNIQUE(user_id,sku_id)`

---

## 7. 订单与售后模块

### 7.1 orders（订单主表）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | 订单ID |
| order_no | `VARCHAR(32)` | NOT NULL, UNIQUE | 订单号（如 `O2026062412345678`） |
| user_id | `BIGINT UNSIGNED` | NOT NULL | |
| merchant_id | `INT UNSIGNED` | NOT NULL | 商家ID（平台级订单可能为0，实际拆单后子订单有值） |
| parent_order_id | `BIGINT UNSIGNED` | NULL | 父订单ID（拆单） |
| order_type | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 1=普通 2=秒杀 3=拼团 4=分销 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 10 | 10=待付款 20=已支付 30=待发货 40=已发货 50=待收货 60=已收货 70=已完成 80=已取消 90=退款中 100=已退款 |
| pay_status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=未支付 20=已支付 30=部分退款 100=全额退款 |
| refund_status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=无退款 10=退款申请中 20=退款中 30=已退款 40=拒绝退款 |
| product_amount | `BIGINT UNSIGNED` | NOT NULL | 商品总金额（分） |
| discount_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 优惠金额（分） |
| freight_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 运费（分） |
| pay_amount | `BIGINT UNSIGNED` | NOT NULL | 实付金额（分） |
| pay_method | `TINYINT UNSIGNED` | NULL | 1=微信 2=支付宝 3=余额 4=银联 |
| pay_time | `TIMESTAMP` | NULL | 支付时间 |
| pay_transaction_id | `VARCHAR(100)` | NULL | 第三方支付流水号 |
| ship_time | `TIMESTAMP` | NULL | 发货时间 |
| receipt_time | `TIMESTAMP` | NULL | 确认收货时间 |
| cancel_time | `TIMESTAMP` | NULL | 取消时间 |
| cancel_reason | `VARCHAR(255)` | NULL | 取消原因 |
| auto_receipt_time | `TIMESTAMP` | NULL | 自动确认收货时间 |
| remark | `VARCHAR(255)` | NULL | 用户备注 |
| source | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 来源 1=PC 2=H5 3=小程序 4=App |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |
| deleted_at | `TIMESTAMP` | NULL | |

**索引**：`idx_user_status(user_id,status)`, `idx_merchant_status(merchant_id,status)`, `idx_order_no(order_no)`, `idx_status_pay(status,pay_status)`, `idx_created(created_at)`, `idx_pay_time(pay_time)`

### 7.2 order_items（订单商品项）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| order_id | `BIGINT UNSIGNED` | NOT NULL | |
| product_id | `BIGINT UNSIGNED` | NOT NULL | |
| sku_id | `BIGINT UNSIGNED` | NOT NULL | |
| merchant_id | `INT UNSIGNED` | NOT NULL | 冗余 |
| product_title | `VARCHAR(200)` | NOT NULL | 商品标题（快照） |
| product_image | `VARCHAR(500)` | NOT NULL | 商品主图（快照） |
| sku_specs | `JSON` | NOT NULL | 规格快照 |
| price | `BIGINT UNSIGNED` | NOT NULL | 下单时单价（分） |
| quantity | `INT UNSIGNED` | NOT NULL | 数量 |
| total_amount | `BIGINT UNSIGNED` | NOT NULL | 小计（分） |
| discount_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 优惠分摊（分） |
| refund_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 已退款金额（分） |
| refund_status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=无 1=申请中 2=已退款 3=拒绝 |
| is_commented | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 是否已评价 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_order(order_id)`, `idx_product(product_id)`

### 7.3 order_shipments（订单物流）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| order_id | `BIGINT UNSIGNED` | NOT NULL | |
| express_company | `VARCHAR(50)` | NOT NULL | 快递公司 |
| express_no | `VARCHAR(50)` | NOT NULL | 快递单号 |
| express_code | `VARCHAR(20)` | NOT NULL | 快递编码（标准编码） |
| ship_status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=运输中 1=已签收 2=异常 |
| logistics_info | `JSON` | NULL | 物流轨迹 `[{"time":"","desc":""}]` |
| ship_time | `TIMESTAMP` | NOT NULL | |
| receipt_time | `TIMESTAMP` | NULL | |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_order(order_id)`, `idx_express_no(express_no)`

### 7.4 order_refunds（售后退款/退货）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| refund_no | `VARCHAR(32)` | NOT NULL, UNIQUE | 退款单号 |
| order_id | `BIGINT UNSIGNED` | NOT NULL | |
| order_item_id | `BIGINT UNSIGNED` | NULL | 可为空（整单退款） |
| user_id | `BIGINT UNSIGNED` | NOT NULL | |
| merchant_id | `INT UNSIGNED` | NOT NULL | |
| type | `TINYINT UNSIGNED` | NOT NULL | 1=仅退款 2=退货退款 3=换货 |
| reason | `VARCHAR(255)` | NOT NULL | 退款原因 |
| reason_type | `TINYINT UNSIGNED` | NOT NULL | 原因分类（系统定义） |
| description | `VARCHAR(500)` | NULL | 补充说明 |
| images | `JSON` | NULL | 凭证图片 |
| apply_amount | `BIGINT UNSIGNED` | NOT NULL | 申请退款金额（分） |
| refund_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 实际退款金额（分） |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待商家处理 1=商家同意 2=商家拒绝 3=退货中 4=平台介入 5=已退款 6=已拒绝 7=用户撤销 |
| merchant_remark | `VARCHAR(255)` | NULL | 商家处理备注 |
| platform_remark | `VARCHAR(255)` | NULL | 平台仲裁备注 |
| return_express_company | `VARCHAR(50)` | NULL | 退货快递公司 |
| return_express_no | `VARCHAR(50)` | NULL | 退货快递单号 |
| return_ship_time | `TIMESTAMP` | NULL | 用户退货发货时间 |
| merchant_receipt_time | `TIMESTAMP` | NULL | 商家收到退货时间 |
| refund_time | `TIMESTAMP` | NULL | 实际退款时间 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_order(order_id)`, `idx_user(user_id)`, `idx_merchant_status(merchant_id,status)`, `idx_refund_no(refund_no)`

### 7.5 product_reviews（商品评价）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| order_id | `BIGINT UNSIGNED` | NOT NULL | |
| order_item_id | `BIGINT UNSIGNED` | NOT NULL | |
| product_id | `BIGINT UNSIGNED` | NOT NULL | |
| sku_id | `BIGINT UNSIGNED` | NOT NULL | |
| user_id | `BIGINT UNSIGNED` | NOT NULL | |
| merchant_id | `INT UNSIGNED` | NOT NULL | |
| rating | `TINYINT UNSIGNED` | NOT NULL | 1-5 星 |
| content | `VARCHAR(1000)` | NULL | 评价内容 |
| images | `JSON` | NULL | 评价图片 |
| is_anonymous | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 是否匿名 |
| is_append | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 是否追评 |
| parent_id | `BIGINT UNSIGNED` | NULL | 追评时指向原评价 |
| merchant_reply | `VARCHAR(500)` | NULL | 商家回复 |
| merchant_reply_at | `TIMESTAMP` | NULL | |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 0=隐藏 1=显示 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_product(product_id)`, `idx_user(user_id)`, `idx_order_item(order_item_id)`, `idx_merchant(merchant_id)`

---

## 8. 支付与财务模块

### 8.1 payments（支付记录）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| payment_no | `VARCHAR(32)` | NOT NULL, UNIQUE | 支付单号 |
| order_id | `BIGINT UNSIGNED` | NOT NULL | 订单ID |
| user_id | `BIGINT UNSIGNED` | NOT NULL | |
| amount | `BIGINT UNSIGNED` | NOT NULL | 支付金额（分） |
| channel | `TINYINT UNSIGNED` | NOT NULL | 1=微信 2=支付宝 3=余额 4=银联 |
| channel_app_id | `VARCHAR(50)` | NULL | 渠道AppID |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待支付 1=支付中 2=成功 3=失败 4=关闭 |
| paid_at | `TIMESTAMP` | NULL | 支付成功时间 |
| transaction_id | `VARCHAR(100)` | NULL | 第三方支付流水号 |
| failure_reason | `VARCHAR(255)` | NULL | 失败原因 |
| client_ip | `VARCHAR(45)` | NULL | 支付IP |
| expired_at | `TIMESTAMP` | NOT NULL | 支付过期时间 |
| notify_raw | `JSON` | NULL | 渠道回调原始数据 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_order(order_id)`, `idx_payment_no(payment_no)`, `idx_transaction(transaction_id)`, `idx_status(status)`

### 8.2 payment_refunds（退款记录）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| refund_no | `VARCHAR(32)` | NOT NULL, UNIQUE | 退款单号 |
| payment_id | `BIGINT UNSIGNED` | NOT NULL | 原支付记录ID |
| order_id | `BIGINT UNSIGNED` | NOT NULL | |
| order_refund_id | `BIGINT UNSIGNED` | NOT NULL | 关联售后单 |
| amount | `BIGINT UNSIGNED` | NOT NULL | 退款金额（分） |
| channel | `TINYINT UNSIGNED` | NOT NULL | 原支付渠道 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待退款 1=退款中 2=成功 3=失败 |
| refunded_at | `TIMESTAMP` | NULL | |
| channel_refund_id | `VARCHAR(100)` | NULL | 渠道退款单号 |
| failure_reason | `VARCHAR(255)` | NULL | |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

### 8.3 profit_sharings（分账记录）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| order_id | `BIGINT UNSIGNED` | NOT NULL | |
| payment_id | `BIGINT UNSIGNED` | NOT NULL | |
| merchant_id | `INT UNSIGNED` | NOT NULL | |
| total_amount | `BIGINT UNSIGNED` | NOT NULL | 订单实付（分） |
| platform_fee | `BIGINT UNSIGNED` | NOT NULL | 平台佣金（分） |
| merchant_amount | `BIGINT UNSIGNED` | NOT NULL | 商家实得（分） |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待分账 1=分账中 2=成功 3=失败 4=回滚 |
| shared_at | `TIMESTAMP` | NULL | 分账成功时间 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

---

## 9. 营销与分销模块

### 9.1 coupons（优惠券）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| merchant_id | `INT UNSIGNED` | NULL | NULL=平台券，有值=商家券 |
| name | `VARCHAR(100)` | NOT NULL | 优惠券名称 |
| type | `TINYINT UNSIGNED` | NOT NULL | 1=满减券 2=折扣券 3=无门槛券 4=兑换券 |
| scope | `TINYINT UNSIGNED` | NOT NULL | 1=全平台 2=指定分类 3=指定商品 4=指定商家 |
| threshold_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 使用门槛（分），0=无门槛 |
| discount_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 优惠金额（分，type=1,3时） |
| discount_rate | `DECIMAL(3,2)` | NULL | 折扣率（type=2时，0.85=85折） |
| max_discount_amount | `BIGINT UNSIGNED` | NULL | 折扣券最高优惠金额（分） |
| total_quantity | `INT UNSIGNED` | NOT NULL | 总发放数量 |
| remaining_quantity | `INT UNSIGNED` | NOT NULL | 剩余数量 |
| limit_per_user | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 每人限领 |
| start_time | `TIMESTAMP` | NOT NULL | 生效时间 |
| end_time | `TIMESTAMP` | NOT NULL | 过期时间 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 0=停用 1=启用 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |
| deleted_at | `TIMESTAMP` | NULL | |

**索引**：`idx_merchant(merchant_id)`, `idx_time(status,start_time,end_time)`

### 9.2 user_coupons（用户领取的优惠券）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| user_id | `BIGINT UNSIGNED` | NOT NULL | |
| coupon_id | `INT UNSIGNED` | NOT NULL | |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=未使用 1=已使用 2=已过期 3=已作废 |
| used_order_id | `BIGINT UNSIGNED` | NULL | 使用订单 |
| used_at | `TIMESTAMP` | NULL | 使用时间 |
| claim_time | `TIMESTAMP` | NOT NULL | 领取时间 |
| expire_time | `TIMESTAMP` | NOT NULL | 过期时间 |
| created_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_user_status(user_id,status)`, `idx_coupon(coupon_id)`

### 9.3 seckill_activities（秒杀活动）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| merchant_id | `INT UNSIGNED` | NULL | NULL=平台活动 |
| name | `VARCHAR(100)` | NOT NULL | 活动名称 |
| start_time | `TIMESTAMP` | NOT NULL | |
| end_time | `TIMESTAMP` | NOT NULL | |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=未开始 1=进行中 2=已结束 3=已取消 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

### 9.4 seckill_items（秒杀商品项）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| activity_id | `INT UNSIGNED` | NOT NULL | |
| product_id | `BIGINT UNSIGNED` | NOT NULL | |
| sku_id | `BIGINT UNSIGNED` | NOT NULL | |
| seckill_price | `BIGINT UNSIGNED` | NOT NULL | 秒杀价（分） |
| stock | `INT UNSIGNED` | NOT NULL | 秒杀库存 |
| sold | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 已售数量 |
| limit_per_user | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 每人限购 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | |
| created_at | `TIMESTAMP` | NOT NULL | |

### 9.5 distributors（分销员）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| user_id | `BIGINT UNSIGNED` | NOT NULL, UNIQUE | 用户ID |
| parent_id | `INT UNSIGNED` | NULL | 上级分销员ID |
| level | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 分销等级 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=待审核 1=正常 2=禁用 |
| total_commission | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 累计佣金（分） |
| available_commission | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 可提现佣金（分） |
| frozen_commission | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 冻结佣金（分） |
| total_order_count | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 推广订单数 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

### 9.6 distribution_orders（分销订单）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| order_id | `BIGINT UNSIGNED` | NOT NULL | |
| order_item_id | `BIGINT UNSIGNED` | NOT NULL | |
| distributor_id | `INT UNSIGNED` | NOT NULL | 分销员ID |
| parent_distributor_id | `INT UNSIGNED` | NULL | 上级分销员 |
| commission_amount | `BIGINT UNSIGNED` | NOT NULL | 佣金（分） |
| parent_commission_amount | `BIGINT UNSIGNED` | NOT NULL DEFAULT 0 | 上级佣金（分） |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=冻结中 1=可结算 2=已结算 3=已失效 |
| settled_at | `TIMESTAMP` | NULL | |
| created_at | `TIMESTAMP` | NOT NULL | |

---

## 10. 内容管理模块

### 10.1 banners（广告位 Banner）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| title | `VARCHAR(100)` | NOT NULL | 标题 |
| image_url | `VARCHAR(500)` | NOT NULL | 图片 |
| link_url | `VARCHAR(500)` | NULL | 跳转链接 |
| position | `VARCHAR(50)` | NOT NULL | 位置标识 `home_top`, `home_middle`, `category_left` |
| sort_order | `INT UNSIGNED` | NOT NULL DEFAULT 0 | |
| start_time | `TIMESTAMP` | NULL | 展示开始 |
| end_time | `TIMESTAMP` | NULL | 展示结束 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

### 10.2 cms_articles（CMS 文章）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| category_id | `INT UNSIGNED` | NOT NULL | 分类 |
| title | `VARCHAR(200)` | NOT NULL | 标题 |
| summary | `VARCHAR(500)` | NULL | 摘要 |
| content | `LONGTEXT` | NOT NULL | 正文（富文本） |
| cover_url | `VARCHAR(500)` | NULL | 封面 |
| author | `VARCHAR(50)` | NULL | 作者 |
| view_count | `INT UNSIGNED` | NOT NULL DEFAULT 0 | 浏览量 |
| sort_order | `INT UNSIGNED` | NOT NULL DEFAULT 0 | |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 0=草稿 1=发布 2=下架 |
| published_at | `TIMESTAMP` | NULL | 发布时间 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |
| deleted_at | `TIMESTAMP` | NULL | |

---

## 11. 系统配置与日志模块

### 11.1 system_configs（系统配置）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `INT UNSIGNED` | PK, AI | |
| group | `VARCHAR(50)` | NOT NULL | 配置分组 `payment`, `order`, `sms` |
| key | `VARCHAR(100)` | NOT NULL | 配置键 |
| value | `TEXT` | NULL | 配置值（JSON 或字符串） |
| type | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 1=字符串 2=JSON 3=数字 4=布尔 5=数组 |
| description | `VARCHAR(255)` | NULL | 配置说明 |
| is_editable | `TINYINT UNSIGNED` | NOT NULL DEFAULT 1 | 是否可后台编辑 |
| created_at | `TIMESTAMP` | NOT NULL | |
| updated_at | `TIMESTAMP` | NOT NULL | |

**索引**：`UNIQUE(group,key)`

### 11.2 admin_operation_logs（管理操作审计日志）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| user_id | `INT UNSIGNED` | NOT NULL | 管理员ID |
| user_type | `TINYINT UNSIGNED` | NOT NULL | 1=平台管理员 2=商家子账号 |
| action | `VARCHAR(100)` | NOT NULL | 操作 `merchants.audit`, `products.forceOffline` |
| method | `VARCHAR(10)` | NOT NULL | HTTP 方法 |
| path | `VARCHAR(255)` | NOT NULL | 请求路径 |
| ip | `VARCHAR(45)` | NOT NULL | |
| user_agent | `VARCHAR(500)` | NULL | |
| request_params | `JSON` | NULL | 请求参数（脱敏后） |
| response_code | `INT UNSIGNED` | NULL | 响应码 |
| description | `VARCHAR(255)` | NULL | 操作描述 |
| created_at | `TIMESTAMP` | NOT NULL | |

**索引**：`idx_user(user_id)`, `idx_action(action)`, `idx_created(created_at)`

### 11.3 sms_logs（短信发送日志）

| 字段 | 类型 | 属性 | 说明 |
|------|------|------|------|
| id | `BIGINT UNSIGNED` | PK, AI | |
| phone | `VARCHAR(20)` | NOT NULL | |
| template_code | `VARCHAR(50)` | NOT NULL | 模板编码 |
| template_params | `JSON` | NULL | 模板参数 |
| content | `VARCHAR(500)` | NULL | 实际内容 |
| type | `TINYINT UNSIGNED` | NOT NULL | 1=验证码 2=通知 3=营销 |
| status | `TINYINT UNSIGNED` | NOT NULL DEFAULT 0 | 0=发送中 1=成功 2=失败 |
| provider | `VARCHAR(50)` | NULL | 服务商 |
| provider_msg_id | `VARCHAR(100)` | NULL | 服务商消息ID |
| failure_reason | `VARCHAR(255)` | NULL | 失败原因 |
| created_at | `TIMESTAMP` | NOT NULL | |

---

## 12. 索引策略

### 12.1 单表索引设计原则

- **WHERE 高频过滤字段**：优先创建联合索引，最左前缀匹配
- **ORDER BY 字段**：如果排序字段不在 WHERE 索引中，单独考虑
- **JOIN 字段**：外键字段必须建索引（如 `order_items.order_id`）
- **UNIQUE 约束**：业务唯一字段（订单号、支付单号、手机号）
- **覆盖索引**：列表查询尽量使用覆盖索引，避免回表
- **前缀索引**：长文本（如 `title`）使用前缀索引（`title(100)`）或 FULLTEXT
- **禁止**：单表索引数量不超过 5 个；避免冗余索引

### 12.2 关键表索引清单

| 表名 | 索引名 | 字段 | 类型 | 说明 |
|------|--------|------|------|------|
| orders | idx_user_status | `user_id`, `status` | 联合 | 用户订单列表 |
| orders | idx_merchant_status | `merchant_id`, `status` | 联合 | 商家订单列表 |
| orders | idx_order_no | `order_no` | UNIQUE | 订单号唯一 |
| orders | idx_created | `created_at` | 普通 | 按时间排序 |
| order_items | idx_order | `order_id` | 普通 | 订单详情查询 |
| products | idx_merchant_status | `merchant_id`, `status` | 联合 | 商家商品列表 |
| products | idx_category_status | `category_id`, `status`, `audit_status` | 联合 | 分类商品筛选 |
| product_skus | idx_product | `product_id` | 普通 | SKU 查询 |
| payments | idx_order | `order_id` | 普通 | 支付查询 |
| payments | idx_payment_no | `payment_no` | UNIQUE | 支付单号唯一 |
| user_coupons | idx_user_status | `user_id`, `status` | 联合 | 我的优惠券 |
| merchant_settlements | idx_merchant_status | `merchant_id`, `status` | 联合 | 商家结算列表 |

---

## 13. 分库分表策略

### 13.1 当前阶段（单库主从）

- 采用 **字段隔离** 方案（`merchant_id`），单数据库实例
- MySQL 主从读写分离（1 主 2 从）
- 所有表通过 `merchant_id` 字段实现租户隔离

### 13.2 未来扩展（分库分表）

| 表 | 分表策略 | 分库策略 | 触发条件 |
|----|---------|---------|---------|
| `orders` | 按时间分片（`orders_YYYYMM`） | 按 `user_id % 16` 分库 | 单表 > 5000万 |
| `order_items` | 按订单ID关联（同订单同分片） | 随订单库 | 单表 > 1亿 |
| `payments` | 按时间分片 | 按 `user_id % 16` | 单表 > 5000万 |
| `wallet_transactions` | 按 `user_id % 128` 分表 | 按 `user_id % 16` | 单表 > 1亿 |
| `product_reviews` | 按 `product_id % 64` | 不分库 | 单表 > 5000万 |
| `admin_operation_logs` | 按时间分片（`YYYYMM`） | 独立日志库 | 单表 > 1亿 |
| `sms_logs` | 按时间分片（`YYYYMM`） | 独立日志库 | 单表 > 1亿 |

**分表实现方案**：使用 ShardingSphere 或自定义中间件（Laravel 数据库连接动态切换）

**全局 ID 生成**：Snowflake（分布式ID，避免自增ID冲突）

---

## 14. 数据字典

### 14.1 通用状态枚举

| 状态类型 | 值 | 含义 |
|----------|----|------|
| 通用状态 | 0 | 禁用/隐藏/待处理/未支付 |
| 通用状态 | 1 | 启用/显示/正常/已支付 |
| 通用状态 | 2 | 已处理/已完成/已通过/退款中 |
| 通用状态 | 3 | 已拒绝/已退款/已关闭 |
| 通用状态 | 4 | 已取消/已冻结/已作废 |
| 通用状态 | 5 | 售后中/平台介入 |

### 14.2 订单状态

> **对应 PHP Enum**：`App\Enums\OrderStatus`（int Backed Enum，与 PRD 文档第 8.4 节状态机定义一致）

| 值 | 状态 | 说明 | 可执行操作 |
|----|------|------|-----------|
| 10 | 待付款 | 订单创建未支付 | 支付、取消 |
| 20 | 已支付 | 支付成功，待商家发货 | 退款申请 |
| 30 | 待发货 | 已支付，商家未发货 | 发货、退款 |
| 40 | 已发货 | 商家已发货，物流中 | 确认收货、查看物流 |
| 50 | 待收货 | 已发货，用户待确认 | 确认收货、申请售后 |
| 60 | 已收货 | 用户已确认收货 | 评价 |
| 70 | 已完成 | 交易完成 | 评价、7天内申请售后 |
| 80 | 已取消 | 订单取消 | 无 |
| 90 | 退款中 | 退款/退货处理中 | 查看售后进度 |
| 100 | 已退款 | 退款完成 | 无 |

### 14.3 售后退款状态

> 退款状态与订单状态使用相同的 decimated 模式，便于状态机流转校验。

| 值 | 状态 |
|----|------|
| 10 | 待商家处理 |
| 20 | 商家同意 |
| 30 | 商家拒绝 |
| 40 | 退货中 |
| 50 | 平台介入 |
| 60 | 已退款 |
| 70 | 已拒绝 |
| 80 | 用户撤销 |

### 14.4 支付渠道

| 值 | 渠道 |
|----|------|
| 1 | 微信支付 |
| 2 | 支付宝 |
| 3 | 余额 |
| 4 | 银联 |

### 14.5 支付状态

| 值 | 状态 |
|----|------|
| 0 | 待支付 |
| 10 | 支付中 |
| 20 | 支付成功 |
| 30 | 支付失败 |
| 40 | 已关闭 |

### 14.6 商家审核状态

| 值 | 状态 |
|----|------|
| 0 | 待审核 |
| 1 | 审核通过 |
| 2 | 审核拒绝 |

### 14.7 商家运营状态

| 值 | 状态 |
|----|------|
| 0 | 待审核（入驻中） |
| 1 | 正常运营 |
| 2 | 冻结 |
| 3 | 关闭 |

> **文档结束**  
> 本数据库设计应随业务迭代持续更新，新增表或变更字段需同步维护本文档。

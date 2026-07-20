# Supplier 通道 P0 核心链路设计文档

> **文档版本**：v1.0  
> **编写日期**：2026/07/08  
> **适用项目**：PHPMall B2B2C 多商户电商平台  
> **设计来源**：基于 `docs/B2B2C-API接口完整度审计报告.md` Phase 2 建议制定  
> **计划周期**：3 周（1 个 Sprint）

---

## 一、设计目标

补齐 `app/Api/Supplier` 通道的 P0 核心接口，使供应商后台能够完成：

1. 入驻申请提交与审核状态查询
2. 供应商登录与资料管理
3. 供货商品 CRUD 与上下架
4. 采购订单列表 / 详情 / 确认 / 发货 / 拒绝 / 取消
5. 库存列表与批量更新
6. 仓库管理（基础 CRUD）
7. 消息通知列表与标记已读

---

## 二、总体约束

| 约束项 | 决策 |
|--------|------|
| 用户体系 | 复用现有 `merchants` + `merchant_staffs` 表，通过 `RoleEnum::Supplier` 区分角色 |
| 认证方式 | 复用 Seller 通道的 JWT `auth:merchant_staff` guard |
| 权限中间件 | 改造 `app/Api/Supplier/Middleware/CheckAuth.php`，校验当前 staff 所属 merchant 的角色为 supplier |
| 数据表 | 沿用 Sprint 0 模式，统一放在 `database/migrations/`，按模块聚合为单个迁移文件 |
| 代码生成 | 迁移表就绪后运行 `php artisan gen:model/service/dao` 生成 Model/Service/Repository |
| 业务层 | `app/Modules/Supplier/Services` 负责供应商域业务逻辑 |
| API 层 | 已有完整 Controller/Request/Response/Route 骨架，仅填充业务逻辑 |

---

## 三、数据模型设计

### 3.1 迁移文件

- `database/migrations/2026_07_08_000001_create_supplier_tables.php`

包含以下表：

| 表名 | 说明 |
|------|------|
| `supplier_profiles` | 供应商入驻资料（营业执照、法人、银行账户、审核状态） |
| `supplier_products` | 供货商品（关联 merchants，区别于 Seller 的 products） |
| `purchase_orders` | 采购订单（平台/商家向供应商采购） |
| `purchase_order_items` | 采购订单明细 |
| `inventory` | 供应商库存快照 |
| `warehouses` | 供应商仓库 |
| `supplier_contracts` | 供应商合同 |
| `supplier_settlements` | 供应商结算单 |
| `supplier_messages` | 供应商站内消息 |

### 3.2 核心字段

#### supplier_profiles

```php
Schema::create('supplier_profiles', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('merchant_id')->unique()->comment('关联商户ID');
    $table->string('company_name')->comment('企业名称');
    $table->string('business_license_no')->nullable()->comment('营业执照号');
    $table->string('business_license_image')->nullable()->comment('营业执照图片');
    $table->string('legal_person_name')->nullable()->comment('法人姓名');
    $table->string('legal_person_idcard')->nullable()->comment('法人身份证');
    $table->string('bank_account_name')->nullable()->comment('开户名');
    $table->string('bank_account_no')->nullable()->comment('银行账号');
    $table->string('bank_name')->nullable()->comment('开户行');
    $table->text('address')->nullable()->comment('经营地址');
    $table->tinyInteger('status')->default(0)->comment('0=待审核 1=通过 2=驳回');
    $table->text('reject_reason')->nullable()->comment('驳回原因');
    $table->timestamps();

    $table->index(['status'], 'idx_supplier_profiles_status');
});
```

#### supplier_products

```php
Schema::create('supplier_products', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('merchant_id')->index()->comment('供应商商户ID');
    $table->string('title')->comment('商品标题');
    $table->string('sku_code')->comment('SKU编码');
    $table->unsignedBigInteger('supply_price')->default(0)->comment('供货价（分）');
    $table->unsignedInteger('stock')->default(0)->comment('库存');
    $table->tinyInteger('status')->default(0)->comment('0=下架 1=上架');
    $table->timestamps();

    $table->unique(['merchant_id', 'sku_code'], 'udx_supplier_products_merchant_sku');
    $table->index(['merchant_id', 'status'], 'idx_supplier_products_merchant_status');
});
```

#### purchase_orders

```php
Schema::create('purchase_orders', function (Blueprint $table): void {
    $table->id();
    $table->string('order_no', 64)->unique()->comment('采购单号');
    $table->foreignId('merchant_id')->index()->comment('供应商商户ID');
    $table->foreignId('buyer_merchant_id')->index()->comment('采购方商户ID');
    $table->unsignedBigInteger('total_amount')->default(0)->comment('总金额（分）');
    $table->tinyInteger('status')->default(10)->comment('10=待确认 20=已确认 30=已发货 40=已完成 50=已拒绝 60=已取消');
    $table->json('address')->comment('收货地址快照');
    $table->string('logistics_company')->nullable()->comment('物流公司');
    $table->string('logistics_no')->nullable()->comment('物流单号');
    $table->timestamp('shipped_at')->nullable()->comment('发货时间');
    $table->timestamp('completed_at')->nullable()->comment('完成时间');
    $table->timestamps();

    $table->index(['merchant_id', 'status'], 'idx_purchase_orders_merchant_status');
});
```

#### purchase_order_items

```php
Schema::create('purchase_order_items', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('purchase_order_id')->index()->comment('采购订单ID');
    $table->foreignId('supplier_product_id')->index()->comment('供货商品ID');
    $table->string('product_title')->comment('商品标题快照');
    $table->string('sku_code')->comment('SKU编码快照');
    $table->unsignedInteger('quantity')->comment('数量');
    $table->unsignedBigInteger('price')->comment('单价（分）');
    $table->unsignedBigInteger('total')->comment('小计（分）');
    $table->timestamps();
});
```

#### inventory

```php
Schema::create('inventory', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('merchant_id')->index()->comment('供应商商户ID');
    $table->foreignId('supplier_product_id')->unique()->comment('供货商品ID');
    $table->unsignedInteger('stock')->default(0)->comment('可用库存');
    $table->unsignedInteger('locked_stock')->default(0)->comment('锁定库存');
    $table->timestamps();

    $table->index(['merchant_id', 'stock'], 'idx_inventory_merchant_stock');
});
```

#### warehouses

```php
Schema::create('warehouses', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('merchant_id')->index()->comment('供应商商户ID');
    $table->string('name')->comment('仓库名称');
    $table->string('contact_name')->nullable()->comment('联系人');
    $table->string('contact_phone')->nullable()->comment('联系电话');
    $table->text('address')->nullable()->comment('仓库地址');
    $table->tinyInteger('status')->default(1)->comment('0=停用 1=启用');
    $table->timestamps();

    $table->index(['merchant_id', 'status'], 'idx_warehouses_merchant_status');
});
```

#### supplier_contracts

```php
Schema::create('supplier_contracts', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('merchant_id')->index()->comment('供应商商户ID');
    $table->string('title')->comment('合同标题');
    $table->text('content')->nullable()->comment('合同内容');
    $table->tinyInteger('status')->default(0)->comment('0=未签署 1=已签署');
    $table->timestamp('signed_at')->nullable()->comment('签署时间');
    $table->timestamps();
});
```

#### supplier_settlements

```php
Schema::create('supplier_settlements', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('merchant_id')->index()->comment('供应商商户ID');
    $table->string('settlement_no')->unique()->comment('结算单号');
    $table->unsignedBigInteger('amount')->default(0)->comment('结算金额（分）');
    $table->tinyInteger('status')->default(0)->comment('0=待结算 1=已结算');
    $table->timestamps();
});
```

#### supplier_messages

```php
Schema::create('supplier_messages', function (Blueprint $table): void {
    $table->id();
    $table->foreignId('merchant_id')->index()->comment('供应商商户ID');
    $table->string('title')->comment('消息标题');
    $table->text('content')->nullable()->comment('消息内容');
    $table->tinyInteger('is_read')->default(0)->comment('0=未读 1=已读');
    $table->timestamps();

    $table->index(['merchant_id', 'is_read'], 'idx_supplier_messages_merchant_read');
});
```

---

## 四、接口实现清单

### 4.1 认证与入驻

| 接口 | Controller | 状态 | 说明 |
|------|-----------|------|------|
| POST /supplier/v1/auth/login | `AuthController@login` | 空壳 | 复用 `auth:merchant_staff` JWT 登录，校验 `RoleEnum::Supplier` |
| POST /supplier/v1/auth/logout | 新增 | 缺失 | 退出登录 |
| GET /supplier/v1/auth/me | 新增 | 缺失 | 当前供应商资料 |
| POST /supplier/v1/auth/refresh | 新增 | 缺失 | Token 刷新 |
| POST /supplier/v1/supplier/register | 新增 | 缺失 | 提交入驻申请 |
| GET /supplier/v1/supplier/audit-status | 新增 | 缺失 | 查询审核状态 |
| PUT /supplier/v1/supplier | `SupplierController@update` | 空壳 | 更新供应商资料 |
| GET /supplier/v1/supplier/profile | `SupplierController@profile` | 空壳 | 供应商资料详情 |

### 4.2 供货商品

| 接口 | Controller | 状态 | 说明 |
|------|-----------|------|------|
| GET /supplier/v1/supply-products | `SupplyProductController@index` | 空壳 | 列表 |
| POST /supplier/v1/supply-products | `SupplyProductController@store` | 空壳 | 创建 |
| GET /supplier/v1/supply-products/{id} | `SupplyProductController@show` | 空壳 | 详情 |
| PUT /supplier/v1/supply-products/{id} | `SupplyProductController@update` | 空壳 | 更新 |
| DELETE /supplier/v1/supply-products/{id} | 新增 | 缺失 | 删除 |
| POST /supplier/v1/supply-products/{id}/on-shelf | 新增 | 缺失 | 上架 |
| POST /supplier/v1/supply-products/{id}/off-shelf | 新增 | 缺失 | 下架 |

### 4.3 采购订单

| 接口 | Controller | 状态 | 说明 |
|------|-----------|------|------|
| GET /supplier/v1/purchase-orders | `PurchaseOrderController@index` | 空壳 | 列表 |
| GET /supplier/v1/purchase-orders/{id} | `PurchaseOrderController@show` | 空壳 | 详情 |
| POST /supplier/v1/purchase-orders/{id}/confirm | `PurchaseOrderController@confirm` | 空壳 | 确认接单 |
| POST /supplier/v1/purchase-orders/{id}/ship | `PurchaseOrderController@ship` | 空壳 | 发货 |
| POST /supplier/v1/purchase-orders/{id}/reject | 新增 | 缺失 | 拒绝 |
| POST /supplier/v1/purchase-orders/{id}/cancel | 新增 | 缺失 | 取消 |

### 4.4 库存

| 接口 | Controller | 状态 | 说明 |
|------|-----------|------|------|
| GET /supplier/v1/inventory | `InventoryController@index` | 空壳 | 列表 |
| PUT /supplier/v1/inventory/{id} | `InventoryController@update` | 空壳 | 更新库存 |
| POST /supplier/v1/inventory/batch | `InventoryController@batchUpdate` | 空壳 | 批量更新 |

### 4.5 仓库

| 接口 | Controller | 状态 | 说明 |
|------|-----------|------|------|
| GET /supplier/v1/warehouses | `WarehouseController@index` | 空壳 | 列表 |
| POST /supplier/v1/warehouses | `WarehouseController@store` | 空壳 | 创建 |
| GET /supplier/v1/warehouses/{id} | `WarehouseController@show` | 空壳 | 详情 |
| PUT /supplier/v1/warehouses/{id} | `WarehouseController@update` | 空壳 | 更新 |
| DELETE /supplier/v1/warehouses/{id} | 新增 | 缺失 | 删除 |

### 4.6 消息

| 接口 | Controller | 状态 | 说明 |
|------|-----------|------|------|
| GET /supplier/v1/messages | `MessageController@index` | 空壳 | 列表 |
| GET /supplier/v1/messages/{id} | `MessageController@show` | 空壳 | 详情 |
| POST /supplier/v1/messages/{id}/read | `MessageController@markRead` | 空壳 | 标记已读 |

### 4.7 合同与结算（只读，P0 仅列表/详情）

| 接口 | Controller | 状态 | 说明 |
|------|-----------|------|------|
| GET /supplier/v1/contracts | `ContractController@index` | 空壳 | 列表 |
| GET /supplier/v1/contracts/{id} | `ContractController@show` | 空壳 | 详情 |
| POST /supplier/v1/contracts/{id}/sign | `ContractController@sign` | 空壳 | 签署 |
| GET /supplier/v1/supplier-settlements | `SupplierSettlementController@index` | 空壳 | 列表 |
| GET /supplier/v1/supplier-settlements/{id} | `SupplierSettlementController@show` | 空壳 | 详情 |

---

## 五、业务规则与状态机

### 5.1 入驻审核状态机

```
待审核 ──(平台审核通过)──► 已通过 ──(资料变更)──► 待审核
   │
   └─(平台驳回)──────────► 已驳回 ──(重新提交)──► 待审核
```

- 只有通过审核的供应商才能登录 Supplier 后台
- 登录时 `CheckAuth` 中间件校验 `merchant.status = active` 且 `supplier_profiles.status = 1`

### 5.2 采购订单状态机

```
待确认 ──(confirm)──► 已确认 ──(ship)──► 已发货 ──(买家确认)──► 已完成
   │                      │
   ├─(reject)────────► 已拒绝                    │
   └─(cancel)────────► 已取消◄───────────────────┘
```

- 待确认状态下可确认 / 拒绝 / 取消
- 已确认状态下可发货
- 发货后不可取消

### 5.3 供货商品状态

```
下架 ──(onShelf)──► 上架 ──(offShelf)──► 下架
```

- 上架商品必须供货价 > 0 且库存 > 0
- 删除商品前需先下架

---

## 六、关键设计决策

### 6.1 用户体系复用

供应商不是独立用户体系，而是 `merchants` 表的一种角色类型：

- `merchant_staffs` 表中的 staff 通过 JWT `auth:merchant_staff` 登录
- `CheckAuth` 中间件读取 `merchant.role_type` 或 `merchant.type` 字段，仅允许 supplier 类型通过
- 入驻申请通过后，自动创建 `merchants` 记录并关联 `supplier_profiles`

### 6.2 数据隔离

所有 Supplier 表都包含 `merchant_id`，Service 层通过 `getCurrentMerchantId()` 强制按商户隔离：

```php
private function getCurrentMerchantId(): int
{
    return auth('merchant_staff')->user()->merchant_id;
}
```

### 6.3 与 Seller/Order 的关系

- 本阶段不实现「平台向供应商自动采购」的触发逻辑
- 采购订单由平台运营后台（Admin）或 Seller 后台手动创建
- Supplier 后台只负责处理已产生的采购订单

### 6.4 库存同步

- `inventory` 表作为供应商库存快照
- 发货时扣减 `inventory.stock`
- 本阶段不做分布式库存锁，依赖数据库乐观锁（`update ... where stock >= ?`）

---

## 七、验收标准

### 7.1 功能验收

| 功能 | 验收标准 |
|------|---------|
| 入驻 | 供应商可提交入驻资料，查询审核状态；审核通过后可登录 |
| 登录 | 仅 supplier 角色的 merchant_staff 可登录 Supplier 后台 |
| 商品 | 供应商可增删改查供货商品，可上下架 |
| 订单 | 供应商可查看采购订单，确认/拒绝/取消/发货 |
| 库存 | 供应商可查看库存列表，单条/批量更新库存 |
| 仓库 | 供应商可管理仓库基础信息 |
| 消息 | 供应商可接收并标记消息已读 |

### 7.2 质量门禁

- PHPStan level 6 无新增错误（或纳入 baseline）
- Pint 代码风格检查通过
- 新增接口必须补充 Feature 测试
- OpenAPI 注解与路由同步更新

---

## 八、风险与应对

| 风险 | 影响 | 应对 |
|------|------|------|
| 复用 merchant_staff 导致 Seller/Supplier 权限混淆 | 高 | CheckAuth 严格校验 role_type |
| 采购订单与 Seller 订单模型概念重叠 | 中 | 命名空间隔离：purchase_orders vs orders |
| 库存并发扣减 | 中 | 使用乐观锁，后续升级到分布式锁 |
| 前端 package/supplier 已有骨架但接口字段可能不符 | 中 | 每完成一个模块即联调 |

---

## 九、下一步动作

1. 技术负责人使用 `superpowers:writing-plans` 生成本设计文档对应的详细实施计划。
2. 按「数据表设计 → gen 生成 Model/Service/Repository → API 实现 → 测试」顺序执行。
3. 每完成一个业务域（入驻/商品/订单/库存）即与 `packages/supplier` 联调。

---

> **文档维护**：本设计文档应在实施过程中随实际发现更新，重大决策变更需记录决策理由。

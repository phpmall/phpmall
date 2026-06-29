# Common/Portal/Shop/Supplier/Seller 模块 DTO Schema 补充设计

## 背景

`app/Api/User/` 模块的 Request/Response DTO Schema 补充已完成，测试基线也已修复。本设计继续处理剩余 5 个非 Admin 模块：Common、Portal、Shop、Supplier、Seller。

## 目标

让 `app/Api/` 下除 Admin 之外的所有模块接口都具备完整的 Request/Response DTO Schema 引用，GET 列表接口的查询参数通过 `#[OA\Parameter]` 正确暴露到 OpenAPI 文档。

## 范围与顺序

按三批推进，每批独立验收：

### 第一批：Portal / Common / Shop（快速收尾）

- **Portal**：
  - 补充 `IndexController::index` 的 Response DTO（空首页响应可保留空 Schema）。
  - 补充 `MarketingController::current` / `upcoming` 的 Response DTO。
- **Common**：无明显缺口，仅做 Schema 完整审计。
- **Shop**：无明显缺口，仅做 Schema 完整审计。

### 第二批：Supplier

- 为以下 8 个缺少 `IndexRequest` 的 `index()` 方法创建 DTO：
  - `ContractController::index`
  - `InventoryController::index`
  - `MessageController::index`
  - `PurchaseOrderController::index`
  - `SupplierController::index`
  - `SupplierSettlementController::index`
  - `SupplyProductController::index`
  - `WarehouseController::index`
- 补充缺失的 `security: [['bearerAuth' => []]]` 注解。
- 补充 `ContractController::download` 等缺失 Response DTO 的 Schema 引用或空响应声明。

### 第三批：Seller

- 为约 29 个缺少 `IndexRequest` 的 `index()` 方法创建 DTO。
- 补充 `IndexController::index` 等缺失 Response DTO 的 Schema 引用。
- Seller 控制器数量最多，作为独立大模块实施。

## 关键约定

沿用 `app/Api/User/` 已验证的模式：

1. **控制器返回原始数据**：不再强制包装 Response DTO，避免破坏现有测试；DTO 仅用于 OpenAPI Schema 声明。
2. **IndexRequest DTO**：
   - 继承 `Illuminate\Foundation\Http\FormRequest`。
   - 顶部声明 `#[OA\Schema]`，字段包括 `page` / `per_page` 及模块特定过滤字段（如 `status`、`type`、`keyword`）。
   - 使用 `const string getXxx = 'xxx'` 字段常量。
   - 提供 `rules()`、`messages()`、`authorize()`。
3. **OA\Parameter 注解**：每个 GET `index()` 方法显式声明查询参数，确保 OpenAPI 生成文档包含 `parameters`。
4. **路径参数**：`show()` / `destroy()` 已有 `id` 参数的不变；缺失的补齐。
5. **Schema 审计**：每批结束时检查现有 Response DTO 是否满足：
   - 类有 `#[OA\Schema]`；
   - 每个属性有 `#[OA\Property]` 且 `description` / `type` 完整；
   - 可空字段标注 `nullable: true`；
   - 日期字段标注 `format: 'date-time'`；
   - 数组字段声明 `items`；
   - 每个属性有 getter/setter。

## 验收标准

每批结束时必须同时通过：

- `./vendor/bin/openapi app/Api/<Module>` 生成无当前模块相关错误；
- `php artisan route:list` 当前模块路由正常加载；
- `vendor/bin/pint app/Api/<Module>/ --test` 通过；
- `php artisan test` 保持全部通过。

## 文件结构示例

新增 Request DTO：

```
app/Api/Supplier/Requests/Contract/ContractIndexRequest.php
app/Api/Supplier/Requests/Inventory/InventoryIndexRequest.php
...
app/Api/Seller/Requests/Product/ProductIndexRequest.php
```

修改控制器：

```
app/Api/Supplier/Controllers/ContractController.php
app/Api/Seller/Controllers/ProductController.php
...
```

## 后续计划

三批全部完成后，`app/Api/` 下除 Admin 外所有模块的接口 Schema 将达到完整、一致、可生成 OpenAPI 文档的状态。

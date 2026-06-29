# Common/Portal/Shop/Supplier/Seller 模块 DTO Schema 补充实施计划

> **For agentic workers:** REQUIRED SUB-SKILL: Use `superpowers:subagent-driven-development` (recommended) or `superpowers:executing-plans` to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** 为 `app/Api/` 下除 Admin/User 之外的 Common、Portal、Shop、Supplier、Seller 模块补齐 Request/Response DTO OpenAPI Schema，使所有 GET 列表接口的查询参数可被文档化。

**Architecture：** 沿用 User 模块已验证模式：为每个缺少 typed request 的 `index()` 方法创建 `*IndexRequest` FormRequest DTO；控制器方法签名改为该 DTO；在 `#[OA\Get]` 上补充 `#[OA\Parameter]` 查询参数注解；控制器仍返回原始模型数据，DTO 仅用于 Schema 声明。

**Tech Stack：** PHP 8.3、Laravel 11、OpenApi Attributes (`zircote/swagger-php`)、`Juling\Foundation\Support\Traits\HasSerializableAttributes`

---

## 文件结构映射

### 第一批：Portal / Common / Shop

**新增 Response DTO（3 个）：**
- `app/Api/Portal/Responses/Index/IndexResponse.php`
- `app/Api/Portal/Responses/Marketing/MarketingCurrentResponse.php`
- `app/Api/Portal/Responses/Marketing/MarketingUpcomingResponse.php`

**修改控制器（3 个）：**
- `app/Api/Portal/Controllers/IndexController.php`
- `app/Api/Portal/Controllers/MarketingController.php`
- （Common/Shop 仅审计，可能无需修改）

### 第二批：Supplier

**新增 Request DTO（8 个）：**
- `app/Api/Supplier/Requests/Contract/ContractIndexRequest.php`
- `app/Api/Supplier/Requests/Inventory/InventoryIndexRequest.php`
- `app/Api/Supplier/Requests/Message/MessageIndexRequest.php`
- `app/Api/Supplier/Requests/PurchaseOrder/PurchaseOrderIndexRequest.php`
- `app/Api/Supplier/Requests/Supplier/SupplierIndexRequest.php`
- `app/Api/Supplier/Requests/SupplierSettlement/SupplierSettlementIndexRequest.php`
- `app/Api/Supplier/Requests/SupplyProduct/SupplyProductIndexRequest.php`
- `app/Api/Supplier/Requests/Warehouse/WarehouseIndexRequest.php`

**修改控制器（8+ 个）：**
- `app/Api/Supplier/Controllers/ContractController.php`
- `app/Api/Supplier/Controllers/InventoryController.php`
- `app/Api/Supplier/Controllers/MessageController.php`
- `app/Api/Supplier/Controllers/PurchaseOrderController.php`
- `app/Api/Supplier/Controllers/SupplierController.php`
- `app/Api/Supplier/Controllers/SupplierSettlementController.php`
- `app/Api/Supplier/Controllers/SupplyProductController.php`
- `app/Api/Supplier/Controllers/WarehouseController.php`

### 第三批：Seller

**新增 Request DTO（约 29 个）：**
按控制器分别创建 `app/Api/Seller/Requests/{Controller}/{Controller}IndexRequest.php`。

**修改控制器（约 29 个）：**
对应每个缺少 typed request 的 `index()` 方法签名。

---

## Task 1: Portal 首页与营销接口 Response DTO

**Files:**
- Create: `app/Api/Portal/Responses/Index/IndexResponse.php`
- Create: `app/Api/Portal/Responses/Marketing/MarketingCurrentResponse.php`
- Create: `app/Api/Portal/Responses/Marketing/MarketingUpcomingResponse.php`
- Modify: `app/Api/Portal/Controllers/IndexController.php`
- Modify: `app/Api/Portal/Controllers/MarketingController.php`

- [ ] **Step 1.1: 创建 IndexResponse**

```php
<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Index;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalIndexResponse')]
class IndexResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'message', description: '欢迎信息', type: 'string')]
    private string $message;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
```

- [ ] **Step 1.2: 创建 MarketingCurrentResponse 和 MarketingUpcomingResponse**

两者结构相同，均只有一个 `message` 字符串字段。分别命名为 `PortalMarketingCurrentResponse` / `PortalMarketingUpcomingResponse`。

```php
<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Marketing;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalMarketingCurrentResponse')]
class MarketingCurrentResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'message', description: '当前营销活动信息', type: 'string')]
    private string $message;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
```

- [ ] **Step 1.3: 修改 Portal IndexController**

将 `#[OA\Response(response: 200, description: 'OK')]` 改为引用 `IndexResponse::class`。

```php
#[OA\Get(path: '/', summary: '首页', security: [[]], tags: ['商城平台'])]
#[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: IndexResponse::class))]
public function index(): JsonResponse
{
    return $this->success();
}
```

- [ ] **Step 1.4: 修改 Portal MarketingController**

将 `current()` 和 `upcoming()` 的 `OA\Response` 分别改为引用 `MarketingCurrentResponse::class` 和 `MarketingUpcomingResponse::class`。

- [ ] **Step 1.5: 验证并提交**

Run:
```bash
./vendor/bin/openapi app/Api/Portal -o /tmp/portal-api.json
php artisan route:list --path=api/portal
vendor/bin/pint app/Api/Portal/ --test
php artisan test
```

Expected: 全部通过。

```bash
git add app/Api/Portal/
git commit -m "feat(portal): 补充首页与营销接口 Response DTO Schema"
```

---

## Task 2: Common / Shop 模块 Schema 审计

**Files:**
- 可能修改：`app/Api/Common/Responses/**/*.php`、`app/Api/Shop/Responses/**/*.php`

- [ ] **Step 2.1: 扫描 Common Response DTO**

Run:
```bash
find app/Api/Common/Responses -name '*.php' | sort
```

- [ ] **Step 2.2: 检查每个 Response DTO 是否满足：**

1. 类有 `#[OA\Schema(schema: '...')]`；
2. 每个私有属性都有 `#[OA\Property]`；
3. `description` / `type` 完整；
4. 可空字段有 `nullable: true`；
5. 日期字段有 `format: 'date-time'`；
6. 数组字段有 `items`；
7. 每个属性有 getter/setter。

- [ ] **Step 2.3: 修复不完整 Schema**

对不符合要求的 DTO 进行修复（参考 User 模块 Task 7）。

- [ ] **Step 2.4: 对 Shop 模块重复 Step 2.1-2.3**

- [ ] **Step 2.5: 验证并提交**

Run:
```bash
./vendor/bin/openapi app/Api/Common -o /tmp/common-api.json
./vendor/bin/openapi app/Api/Shop -o /tmp/shop-api.json
php artisan route:list --path=api/shop
vendor/bin/pint app/Api/Common/ app/Api/Shop/ --test
php artisan test
```

Expected: 全部通过。

```bash
git add app/Api/Common/Responses/ app/Api/Shop/Responses/
git commit -m "docs(common/shop): 完善 Common/Shop 模块 Response DTO Schema"
```

---

## Task 3: Supplier 模块 Index Request DTO（合同/库存）

**Files:**
- Create: `app/Api/Supplier/Requests/Contract/ContractIndexRequest.php`
- Create: `app/Api/Supplier/Requests/Inventory/InventoryIndexRequest.php`
- Modify: `app/Api/Supplier/Controllers/ContractController.php`
- Modify: `app/Api/Supplier/Controllers/InventoryController.php`

- [ ] **Step 3.1: 创建 ContractIndexRequest**

```php
<?php

declare(strict_types=1);

namespace App\Api\Supplier\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierContractIndexRequest',
    properties: [
        new OA\Property(property: self::getStatus, description: '合同状态', type: 'integer', nullable: true),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class ContractIndexRequest extends FormRequest
{
    const string getStatus = 'status';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getStatus => ['sometimes', 'integer'],
            self::getPage => ['sometimes', 'integer', 'min:1'],
            self::getPerPage => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getStatus.'.integer' => '状态必须是整数',
            self::getPage.'.integer' => '页码必须是整数',
            self::getPage.'.min' => '页码不能小于1',
            self::getPerPage.'.integer' => '每页数量必须是整数',
            self::getPerPage.'.max' => '每页数量不能超过100',
        ];
    }
}
```

- [ ] **Step 3.2: 创建 InventoryIndexRequest**

与 `ContractIndexRequest` 结构相同，schema 名 `SupplierInventoryIndexRequest`，字段 `status` / `page` / `per_page`。

- [ ] **Step 3.3: 修改 ContractController / InventoryController**

将 `index(Request $request)` 改为 `index(ContractIndexRequest $request)` / `index(InventoryIndexRequest $request)`，并补充 `#[OA\Parameter]` 注解：

```php
#[OA\Get(path: '/contracts', summary: '合同列表', security: [['bearerAuth' => []]], tags: ['供应商'])]
#[OA\Parameter(name: 'status', description: '合同状态', in: 'query', schema: new OA\Schema(type: 'integer', nullable: true))]
#[OA\Parameter(name: 'page', description: '当前页码', in: 'query', schema: new OA\Schema(type: 'integer', example: 1))]
#[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', schema: new OA\Schema(type: 'integer', example: 20))]
#[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractListResponse::class))]
public function index(ContractIndexRequest $request): JsonResponse
```

- [ ] **Step 3.4: 提交**

```bash
git add app/Api/Supplier/Requests/Contract/ app/Api/Supplier/Requests/Inventory/
git add app/Api/Supplier/Controllers/ContractController.php app/Api/Supplier/Controllers/InventoryController.php
git commit -m "feat(supplier): 为 Contract/Inventory 列表接口补充 Index Request DTO"
```

---

## Task 4: Supplier 模块 Index Request DTO（消息/采购订单）

**Files:**
- Create: `app/Api/Supplier/Requests/Message/MessageIndexRequest.php`
- Create: `app/Api/Supplier/Requests/PurchaseOrder/PurchaseOrderIndexRequest.php`
- Modify: `app/Api/Supplier/Controllers/MessageController.php`
- Modify: `app/Api/Supplier/Controllers/PurchaseOrderController.php`

- [ ] **Step 4.1: 创建 MessageIndexRequest**

字段：`is_read`（nullable integer, in:0,1）、`page`、`per_page`。

- [ ] **Step 4.2: 创建 PurchaseOrderIndexRequest**

字段：`status`（nullable integer）、`page`、`per_page`。

- [ ] **Step 4.3: 修改 MessageController / PurchaseOrderController**

替换 `index(Request $request)` 为对应 typed request，补充 `#[OA\Parameter]` 注解，并确保 `security` 属性包含 `bearerAuth`。

- [ ] **Step 4.4: 提交**

```bash
git add app/Api/Supplier/Requests/Message/ app/Api/Supplier/Requests/PurchaseOrder/
git add app/Api/Supplier/Controllers/MessageController.php app/Api/Supplier/Controllers/PurchaseOrderController.php
git commit -m "feat(supplier): 为 Message/PurchaseOrder 列表接口补充 Index Request DTO"
```

---

## Task 5: Supplier 模块 Index Request DTO（供应商/结算/供应商品/仓库）

**Files:**
- Create: `app/Api/Supplier/Requests/Supplier/SupplierIndexRequest.php`
- Create: `app/Api/Supplier/Requests/SupplierSettlement/SupplierSettlementIndexRequest.php`
- Create: `app/Api/Supplier/Requests/SupplyProduct/SupplyProductIndexRequest.php`
- Create: `app/Api/Supplier/Requests/Warehouse/WarehouseIndexRequest.php`
- Modify: 对应 4 个控制器

- [ ] **Step 5.1: 创建 4 个 IndexRequest DTO**

- `SupplierIndexRequest`：字段 `status`、`page`、`per_page`
- `SupplierSettlementIndexRequest`：字段 `status`、`page`、`per_page`
- `SupplyProductIndexRequest`：字段 `status`、`page`、`per_page`
- `WarehouseIndexRequest`：字段 `status`、`page`、`per_page`

- [ ] **Step 5.2: 修改 4 个控制器**

替换 `index(Request $request)` 为对应 typed request，补充 `#[OA\Parameter]` 和 `security` 注解。

- [ ] **Step 5.3: 验证并提交**

Run:
```bash
./vendor/bin/openapi app/Api/Supplier -o /tmp/supplier-api.json
php artisan route:list --path=api/supplier
vendor/bin/pint app/Api/Supplier/ --test
php artisan test
```

Expected: 全部通过。

```bash
git add app/Api/Supplier/
git commit -m "feat(supplier): 为 Supplier/Settlement/SupplyProduct/Warehouse 列表接口补充 Index Request DTO"
```

---

## Task 6: Supplier 模块缺失 Response DTO 与安全注解

**Files:**
- 可能创建：`app/Api/Supplier/Responses/Contract/ContractDownloadResponse.php`
- 可能修改：多个 Supplier 控制器

- [ ] **Step 6.1: 处理 ContractController::download**

如果该方法返回文件下载，可保持 `OA\Response` 为空；若需文档化，创建一个简单的 `ContractDownloadResponse` 并引用。

- [ ] **Step 6.2: 扫描并补充缺失的 `security` 注解**

对所有 Supplier GET 接口检查是否声明 `security: [['bearerAuth' => []]]`，缺失则补齐。

- [ ] **Step 6.3: 提交**

```bash
git add app/Api/Supplier/
git commit -m "docs(supplier): 补充 Supplier 模块 Response DTO 与安全注解"
```

---

## Task 7: Seller 模块 Index Request DTO（商品相关）

**Files:**
- Create: 8 个 Request DTO
- Modify: 8 个控制器

- [ ] **Step 7.1: 创建以下 Seller IndexRequest DTO**

| DTO 文件 | Schema 名称 | 字段 |
|----------|-------------|------|
| `app/Api/Seller/Requests/Product/ProductIndexRequest.php` | `SellerProductIndexRequest` | `status`, `keyword`, `category_id`, `page`, `per_page` |
| `app/Api/Seller/Requests/ProductSku/ProductSkuIndexRequest.php` | `SellerProductSkuIndexRequest` | `product_id`, `page`, `per_page` |
| `app/Api/Seller/Requests/ProductAttribute/ProductAttributeIndexRequest.php` | `SellerProductAttributeIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/ProductAudit/ProductAuditIndexRequest.php` | `SellerProductAuditIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/Brand/BrandIndexRequest.php` | `SellerBrandIndexRequest` | `status`, `keyword`, `page`, `per_page` |
| `app/Api/Seller/Requests/Category/CategoryIndexRequest.php` | `SellerCategoryIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/ShopCategory/ShopCategoryIndexRequest.php` | `SellerShopCategoryIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/FreightTemplate/FreightTemplateIndexRequest.php` | `SellerFreightTemplateIndexRequest` | `page`, `per_page` |

每个 DTO 均继承 `FormRequest`，含 `OA\Schema`、`rules()`、`messages()`、`authorize()`。

- [ ] **Step 7.2: 修改对应 8 个控制器**

将 `index(Request $request)` 改为对应 typed request，并补充 `#[OA\Parameter]` 注解。

- [ ] **Step 7.3: 提交**

```bash
git add app/Api/Seller/Requests/Product/ app/Api/Seller/Requests/ProductSku/ ...
git add app/Api/Seller/Controllers/ProductController.php ...
git commit -m "feat(seller): 为商品相关列表接口补充 Index Request DTO"
```

---

## Task 8: Seller 模块 Index Request DTO（订单/售后/库存/物流）

**Files:**
- Create: 8 个 Request DTO
- Modify: 8 个控制器

- [ ] **Step 8.1: 创建以下 Seller IndexRequest DTO**

| DTO 文件 | Schema 名称 | 字段 |
|----------|-------------|------|
| `app/Api/Seller/Requests/Order/OrderIndexRequest.php` | `SellerOrderIndexRequest` | `status`, `keyword`, `page`, `per_page` |
| `app/Api/Seller/Requests/SubOrder/SubOrderIndexRequest.php` | `SellerSubOrderIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/Refund/RefundIndexRequest.php` | `SellerRefundIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/Inventory/InventoryIndexRequest.php` | `SellerInventoryIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/InventoryReservation/InventoryReservationIndexRequest.php` | `SellerInventoryReservationIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/InventoryTransaction/InventoryTransactionIndexRequest.php` | `SellerInventoryTransactionIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/Shipment/ShipmentIndexRequest.php` | `SellerShipmentIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/Waybill/WaybillIndexRequest.php` | `SellerWaybillIndexRequest` | `status`, `page`, `per_page` |

- [ ] **Step 8.2: 修改对应 8 个控制器**

替换 `index(Request $request)` 并补充 `#[OA\Parameter]`。

- [ ] **Step 8.3: 提交**

```bash
git add app/Api/Seller/Requests/Order/ ...
git commit -m "feat(seller): 为订单/售后/库存/物流列表接口补充 Index Request DTO"
```

---

## Task 9: Seller 模块 Index Request DTO（店铺/营销/财务/商家）

**Files:**
- Create: 13 个 Request DTO
- Modify: 13 个控制器

- [ ] **Step 9.1: 创建以下 Seller IndexRequest DTO**

| DTO 文件 | Schema 名称 | 字段 |
|----------|-------------|------|
| `app/Api/Seller/Requests/Shop/ShopIndexRequest.php` | `SellerShopIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/ShopDecoration/ShopDecorationIndexRequest.php` | `SellerShopDecorationIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/ShopReview/ShopReviewIndexRequest.php` | `SellerShopReviewIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/Coupon/CouponIndexRequest.php` | `SellerCouponIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/Promotion/PromotionIndexRequest.php` | `SellerPromotionIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/SeckillActivity/SeckillActivityIndexRequest.php` | `SellerSeckillActivityIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/Merchant/MerchantIndexRequest.php` | `SellerMerchantIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/MerchantQualification/MerchantQualificationIndexRequest.php` | `SellerMerchantQualificationIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/MerchantSettlementAccount/MerchantSettlementAccountIndexRequest.php` | `SellerMerchantSettlementAccountIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/Settlement/SettlementIndexRequest.php` | `SellerSettlementIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/Wallet/WalletIndexRequest.php` | `SellerWalletIndexRequest` | `page`, `per_page` |
| `app/Api/Seller/Requests/Withdraw/WithdrawIndexRequest.php` | `SellerWithdrawIndexRequest` | `status`, `page`, `per_page` |
| `app/Api/Seller/Requests/Warehouse/WarehouseIndexRequest.php` | `SellerWarehouseIndexRequest` | `status`, `page`, `per_page` |

- [ ] **Step 9.2: 修改对应 13 个控制器**

替换 `index(Request $request)` 并补充 `#[OA\Parameter]`。

- [ ] **Step 9.3: 验证并提交**

Run:
```bash
./vendor/bin/openapi app/Api/Seller -o /tmp/seller-api.json
php artisan route:list --path=api/seller
vendor/bin/pint app/Api/Seller/ --test
php artisan test
```

Expected: 全部通过。

```bash
git add app/Api/Seller/
git commit -m "feat(seller): 为店铺/营销/财务/商家列表接口补充 Index Request DTO"
```

---

## Task 10: Seller 模块缺失 Response DTO 与 Schema 审计

**Files:**
- 可能创建：`app/Api/Seller/Responses/Index/IndexResponse.php`
- 可能修改：`app/Api/Seller/Responses/**/*.php`

- [ ] **Step 10.1: 补充 Seller IndexController Response DTO**

创建 `app/Api/Seller/Responses/Index/IndexResponse.php`（含 `message` 字符串字段），并在 `IndexController::index` 中引用。

- [ ] **Step 10.2: 审计 Seller 所有 Response DTO**

检查所有 `app/Api/Seller/Responses/**/*.php` 是否满足 Schema 完整 checklist（同 Task 2）。

- [ ] **Step 10.3: 修复不完整 Schema**

- [ ] **Step 10.4: 提交**

```bash
git add app/Api/Seller/
git commit -m "docs(seller): 补充 Seller 模块 Response DTO 并完善 Schema"
```

---

## Task 11: 全量 OpenAPI 生成与回归测试

**Files:**
- 无新增文件，仅校验

- [ ] **Step 11.1: 生成所有非 Admin 模块 OpenAPI**

Run:
```bash
./vendor/bin/openapi app/Api/Common -o /tmp/common-api.json
./vendor/bin/openapi app/Api/Portal -o /tmp/portal-api.json
./vendor/bin/openapi app/Api/Shop -o /tmp/shop-api.json
./vendor/bin/openapi app/Api/Supplier -o /tmp/supplier-api.json
./vendor/bin/openapi app/Api/Seller -o /tmp/seller-api.json
```

Expected: 全部成功退出，无当前模块相关新增错误。

- [ ] **Step 11.2: 路由与风格检查**

Run:
```bash
php artisan route:list
vendor/bin/pint app/Api/ --test
```

Expected: 路由正常，风格通过。

- [ ] **Step 11.3: 测试回归**

Run:
```bash
php artisan test
```

Expected: 14/14 通过。

- [ ] **Step 11.4: 提交修复**

```bash
git add -A
git commit -m "chore(api): 修复全量 OpenAPI 生成与代码风格问题"
```

---

## 自检清单

- [ ] **Spec 覆盖：** 三批模块（Portal/Common/Shop、Supplier、Seller）均有对应任务。
- [ ] **无占位符：** 无 “TBD/TODO/稍后实现”；每个 DTO 都给出了字段和文件路径。
- [ ] **类型一致性：** 所有 IndexRequest 使用 `page` / `per_page`；Seller DTO 统一加 `Seller` 前缀；Supplier DTO 统一加 `Supplier` 前缀。
- [ ] **可执行性：** 每个任务包含文件路径、代码示例、验证命令和提交信息。

## 执行方式选择

**Plan complete and saved to `docs/superpowers/plans/2026-06-29-api-modules-dto-schema-plan.md`. Two execution options:**

**1. Subagent-Driven (recommended)** - I dispatch a fresh subagent per task, review between tasks, fast iteration

**2. Inline Execution** - Execute tasks in this session using `executing-plans`, batch execution with checkpoints

**Which approach?**

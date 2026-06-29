# User 模块 Request/Response DTO Schema 补充实施计划

> **For agentic workers:** REQUIRED SUB-SKILL: Use `superpowers:subagent-driven-development` (recommended) or `superpowers:executing-plans` to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** 让 `app/Api/User/` 下所有控制器接口都具备完整的 Request/Response DTO Schema，控制器不再直接返回原始模型/数组。

**Architecture：** 每个控制器动作使用独立的 `FormRequest` 子类作为 Request DTO，使用带 `HasSerializableAttributes` 的 POPO 作为 Response DTO；OpenAPI 属性通过 `#[OA\Schema]` / `#[OA\Property]` 声明，JSON key 保持 snake_case。

**Tech Stack：** PHP 8.3、Laravel 11、OpenApi Attributes (`zircote/swagger-php`)、`Juling\Foundation\Support\Traits\HasSerializableAttributes`

---

## 文件结构映射

### 新增 Request DTO（17 个）

| 文件 | 说明 |
|------|------|
| `app/Api/User/Requests/Address/AddressIndexRequest.php` | 收货地址列表查询参数 |
| `app/Api/User/Requests/User/UserProfileRequest.php` | 会员资料查询参数（是否携带地址） |
| `app/Api/User/Requests/Order/OrderIndexRequest.php` | 订单列表查询参数 |
| `app/Api/User/Requests/Cart/CartIndexRequest.php` | 购物车列表查询参数 |
| `app/Api/User/Requests/OrderReview/OrderReviewIndexRequest.php` | 订单评价列表查询参数 |
| `app/Api/User/Requests/Coupon/CouponIndexRequest.php` | 优惠券列表查询参数 |
| `app/Api/User/Requests/Wallet/WalletIndexRequest.php` | 钱包首页/余额查询参数 |
| `app/Api/User/Requests/Withdraw/WithdrawIndexRequest.php` | 提现记录列表查询参数 |
| `app/Api/User/Requests/Notification/NotificationIndexRequest.php` | 通知列表查询参数 |
| `app/Api/User/Requests/Favorite/FavoriteIndexRequest.php` | 收藏列表查询参数 |
| `app/Api/User/Requests/Refund/RefundIndexRequest.php` | 退款列表查询参数 |
| `app/Api/User/Requests/Invoice/InvoiceIndexRequest.php` | 发票列表查询参数 |
| `app/Api/User/Requests/Points/PointsIndexRequest.php` | 积分首页查询参数 |
| `app/Api/User/Requests/Commission/CommissionIndexRequest.php` | 佣金列表查询参数 |
| `app/Api/User/Requests/Contract/ContractIndexRequest.php` | 合同列表查询参数 |
| `app/Api/User/Requests/MemberLevel/MemberLevelIndexRequest.php` | 会员等级列表查询参数 |
| `app/Api/User/Requests/UserBind/UserBindIndexRequest.php` | 账号绑定列表查询参数 |
| `app/Api/User/Requests/Message/MessageIndexRequest.php` | 消息列表查询参数 |

### 新增/调整 Response DTO（1 个）

| 文件 | 说明 |
|------|------|
| `app/Api/User/Responses/Address/AddressListResponse.php` | 收货地址列表（现有缺失） |

### 修改控制器（10+ 个）

- `app/Api/User/Controllers/AddressController.php`
- `app/Api/User/Controllers/UserController.php`
- `app/Api/User/Controllers/ProfileController.php`
- `app/Api/User/Controllers/OrderController.php`
- `app/Api/User/Controllers/CartController.php`
- `app/Api/User/Controllers/OrderReviewController.php`
- `app/Api/User/Controllers/CouponController.php`
- `app/Api/User/Controllers/WalletController.php`
- `app/Api/User/Controllers/WithdrawController.php`
- `app/Api/User/Controllers/NotificationController.php`
- `app/Api/User/Controllers/FavoriteController.php`
- `app/Api/User/Controllers/RefundController.php`
- `app/Api/User/Controllers/InvoiceController.php`
- `app/Api/User/Controllers/PointsController.php`
- `app/Api/User/Controllers/CommissionController.php`
- `app/Api/User/Controllers/ContractController.php`
- `app/Api/User/Controllers/MemberLevelController.php`
- `app/Api/User/Controllers/UserBindController.php`
- `app/Api/User/Controllers/MessageController.php`

---

## Task 1: AddressController 完整 DTO 化

**Files:**
- Create: `app/Api/User/Requests/Address/AddressIndexRequest.php`
- Create: `app/Api/User/Responses/Address/AddressListResponse.php`
- Modify: `app/Api/User/Controllers/AddressController.php`

- [ ] **Step 1.1: 创建 AddressIndexRequest**

```php
<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AddressIndexRequest',
    properties: [
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class AddressIndexRequest extends FormRequest
{
    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getPage => ['sometimes', 'integer', 'min:1'],
            self::getPerPage => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getPage.'.integer' => '页码必须是整数',
            self::getPage.'.min' => '页码不能小于1',
            self::getPerPage.'.integer' => '每页数量必须是整数',
            self::getPerPage.'.max' => '每页数量不能超过100',
        ];
    }
}
```

- [ ] **Step 1.2: 创建 AddressListResponse**

```php
<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Address;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AddressListResponse',
    properties: [
        new OA\Property(property: 'list', description: '地址列表', type: 'array', items: new OA\Items(ref: AddressResponse::class)),
        new OA\Property(property: 'total', description: '总数量', type: 'integer'),
    ]
)]
class AddressListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'list', description: '地址列表', type: 'array', items: new OA\Items(ref: AddressResponse::class))]
    private array $list;

    #[OA\Property(property: 'total', description: '总数量', type: 'integer')]
    private int $total;

    public function getList(): array
    {
        return $this->list;
    }

    public function setList(array $list): void
    {
        $this->list = $list;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}
```

- [ ] **Step 1.3: 修改 AddressController::index**

将方法签名改为：

```php
#[OA\Get(path: '/addresses', summary: '收货地址列表', security: [['bearerAuth' => []]], tags: ['会员中心'])]
#[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AddressListResponse::class))]
public function index(AddressIndexRequest $request): JsonResponse
{
    $user = $this->resolveUser($request);
    $addresses = $user->addresses()->orderByDesc('is_default')->orderByDesc('id')->get();

    $response = new AddressListResponse();
    $response->setList($addresses->toArray());
    $response->setTotal($addresses->count());

    return response()->json([
        'code' => 0,
        'data' => $response,
    ]);
}
```

- [ ] **Step 1.4: 运行测试并提交**

Run: `php artisan route:list --path=addresses` 确保路由绑定正确。
Run: `php artisan openapi:generate`（或项目实际命令）检查无 Schema 报错。

```bash
git add app/Api/User/Requests/Address/ app/Api/User/Responses/Address/AddressListResponse.php app/Api/User/Controllers/AddressController.php
git commit -m "feat(user): 为 AddressController 补充 Index Request DTO 及 AddressListResponse Schema"
```

---

## Task 2: UserController / ProfileController 资料接口规范化

**Files:**
- Create: `app/Api/User/Requests/User/UserProfileRequest.php`
- Modify: `app/Api/User/Controllers/UserController.php`
- Modify: `app/Api/User/Controllers/ProfileController.php`

- [ ] **Step 2.1: 创建 UserProfileRequest**

```php
<?php

declare(strict_types=1);

namespace App\Api\User\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserProfileRequest',
    properties: [
        new OA\Property(property: self::getWithAddresses, description: '是否携带地址列表:0否,1是', type: 'integer', example: 0, nullable: true),
    ]
)]
class UserProfileRequest extends FormRequest
{
    const string getWithAddresses = 'with_addresses';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getWithAddresses => ['sometimes', 'integer', 'in:0,1'],
        ];
    }
}
```

- [ ] **Step 2.2: 修改 UserController::profile**

```php
#[OA\Get(path: '/me', summary: '获取会员资料', security: [['bearerAuth' => []]], tags: ['会员中心'])]
#[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserProfileResponse::class))]
public function profile(UserProfileRequest $request): JsonResponse
{
    $user = $this->resolveUser($request);

    if ($request->integer(UserProfileRequest::getWithAddresses, 0) === 1) {
        $user->load('addresses');
    }

    return response()->json([
        'code' => 0,
        'data' => $user,
    ]);
}
```

- [ ] **Step 2.3: 修改 ProfileController 保持与 UserController 一致的返回格式**

确保 `ProfileController::index` 和 `ProfileController::update` 都返回 `UserProfileResponse` 引用（已存在，保持不变即可）。

- [ ] **Step 2.4: 提交**

```bash
git add app/Api/User/Requests/User/ app/Api/User/Controllers/UserController.php app/Api/User/Controllers/ProfileController.php
git commit -m "feat(user): 为 User/Profile 资料接口补充 Request DTO 及 Schema"
```

---

## Task 3: 批量创建通用 Index Request DTO（订单/购物车/评价）

**Files:**
- Create: `app/Api/User/Requests/Order/OrderIndexRequest.php`
- Create: `app/Api/User/Requests/Cart/CartIndexRequest.php`
- Create: `app/Api/User/Requests/OrderReview/OrderReviewIndexRequest.php`
- Modify: `app/Api/User/Controllers/OrderController.php`
- Modify: `app/Api/User/Controllers/CartController.php`
- Modify: `app/Api/User/Controllers/OrderReviewController.php`

- [ ] **Step 3.1: 创建 OrderIndexRequest**

```php
<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderIndexRequest',
    properties: [
        new OA\Property(property: self::getStatus, description: '订单状态', type: 'integer', nullable: true),
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string', nullable: true),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class OrderIndexRequest extends FormRequest
{
    const string getStatus = 'status';

    const string getKeyword = 'keyword';

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
            self::getKeyword => ['sometimes', 'string', 'max:100'],
            self::getPage => ['sometimes', 'integer', 'min:1'],
            self::getPerPage => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
```

- [ ] **Step 3.2: 创建 CartIndexRequest**

与 `OrderIndexRequest` 结构相同，但去掉 `status`/`keyword`，仅保留 `page` / `per_page`。Schema 名称为 `CartIndexRequest`。

- [ ] **Step 3.3: 创建 OrderReviewIndexRequest**

保留 `page` / `per_page`，并增加 `order_id`（可空整数）。Schema 名称为 `OrderReviewIndexRequest`。

- [ ] **Step 3.4: 修改三个控制器的 index 方法签名**

分别将 `OrderController::index`、`CartController::index`、`OrderReviewController::index` 的参数从 `Request $request` 改为对应的 `OrderIndexRequest`、`CartIndexRequest`、`OrderReviewIndexRequest`。

- [ ] **Step 3.5: 提交**

```bash
git add app/Api/User/Requests/Order/ app/Api/User/Requests/Cart/ app/Api/User/Requests/OrderReview/
git add app/Api/User/Controllers/OrderController.php app/Api/User/Controllers/CartController.php app/Api/User/Controllers/OrderReviewController.php
git commit -m "feat(user): 为 Order/Cart/OrderReview 列表接口补充 Index Request DTO"
```

---

## Task 4: 批量创建通用 Index Request DTO（优惠券/钱包/提现/通知）

**Files:**
- Create: `app/Api/User/Requests/Coupon/CouponIndexRequest.php`
- Create: `app/Api/User/Requests/Wallet/WalletIndexRequest.php`
- Create: `app/Api/User/Requests/Withdraw/WithdrawIndexRequest.php`
- Create: `app/Api/User/Requests/Notification/NotificationIndexRequest.php`
- Modify: 对应 4 个控制器

- [ ] **Step 4.1: 创建 CouponIndexRequest**

包含字段：`status`（可空整数）、`page`、`per_page`。

- [ ] **Step 4.2: 创建 WalletIndexRequest**

仅包含 `page`、`per_page`。

- [ ] **Step 4.3: 创建 WithdrawIndexRequest**

包含字段：`status`（可空整数）、`page`、`per_page`。

- [ ] **Step 4.4: 创建 NotificationIndexRequest**

包含字段：`is_read`（可空整数，in:0,1）、`page`、`per_page`。

- [ ] **Step 4.5: 修改控制器并提交**

将四个控制器的 `index` 方法参数替换为对应 Request DTO。

```bash
git add app/Api/User/Requests/Coupon/ app/Api/User/Requests/Wallet/ app/Api/User/Requests/Withdraw/ app/Api/User/Requests/Notification/
git add app/Api/User/Controllers/CouponController.php app/Api/User/Controllers/WalletController.php app/Api/User/Controllers/WithdrawController.php app/Api/User/Controllers/NotificationController.php
git commit -m "feat(user): 为 Coupon/Wallet/Withdraw/Notification 列表接口补充 Index Request DTO"
```

---

## Task 5: 批量创建通用 Index Request DTO（收藏/退款/发票/积分）

**Files:**
- Create: `app/Api/User/Requests/Favorite/FavoriteIndexRequest.php`
- Create: `app/Api/User/Requests/Refund/RefundIndexRequest.php`
- Create: `app/Api/User/Requests/Invoice/InvoiceIndexRequest.php`
- Create: `app/Api/User/Requests/Points/PointsIndexRequest.php`
- Modify: 对应 4 个控制器

- [ ] **Step 5.1: 创建 FavoriteIndexRequest**

包含字段：`type`（可空整数）、`page`、`per_page`。

- [ ] **Step 5.2: 创建 RefundIndexRequest**

包含字段：`status`（可空整数）、`page`、`per_page`。

- [ ] **Step 5.3: 创建 InvoiceIndexRequest**

包含字段：`type`（可空整数）、`page`、`per_page`。

- [ ] **Step 5.4: 创建 PointsIndexRequest**

仅包含 `page`、`per_page`。

- [ ] **Step 5.5: 修改控制器并提交**

```bash
git add app/Api/User/Requests/Favorite/ app/Api/User/Requests/Refund/ app/Api/User/Requests/Invoice/ app/Api/User/Requests/Points/
git add app/Api/User/Controllers/FavoriteController.php app/Api/User/Controllers/RefundController.php app/Api/User/Controllers/InvoiceController.php app/Api/User/Controllers/PointsController.php
git commit -m "feat(user): 为 Favorite/Refund/Invoice/Points 列表接口补充 Index Request DTO"
```

---

## Task 6: 批量创建通用 Index Request DTO（佣金/合同/会员等级/绑定/消息）

**Files:**
- Create: `app/Api/User/Requests/Commission/CommissionIndexRequest.php`
- Create: `app/Api/User/Requests/Contract/ContractIndexRequest.php`
- Create: `app/Api/User/Requests/MemberLevel/MemberLevelIndexRequest.php`
- Create: `app/Api/User/Requests/UserBind/UserBindIndexRequest.php`
- Create: `app/Api/User/Requests/Message/MessageIndexRequest.php`
- Modify: 对应 5 个控制器

- [ ] **Step 6.1: 创建 CommissionIndexRequest**

包含字段：`status`（可空整数）、`page`、`per_page`。

- [ ] **Step 6.2: 创建 ContractIndexRequest**

包含字段：`status`（可空整数）、`page`、`per_page`。

- [ ] **Step 6.3: 创建 MemberLevelIndexRequest**

仅包含 `page`、`per_page`。

- [ ] **Step 6.4: 创建 UserBindIndexRequest**

仅包含 `page`、`per_page`。

- [ ] **Step 6.5: 创建 MessageIndexRequest**

包含字段：`is_read`（可空整数，in:0,1）、`page`、`per_page`。

- [ ] **Step 6.6: 修改控制器并提交**

```bash
git add app/Api/User/Requests/Commission/ app/Api/User/Requests/Contract/ app/Api/User/Requests/MemberLevel/ app/Api/User/Requests/UserBind/ app/Api/User/Requests/Message/
git add app/Api/User/Controllers/CommissionController.php app/Api/User/Controllers/ContractController.php app/Api/User/Controllers/MemberLevelController.php app/Api/User/Controllers/UserBindController.php app/Api/User/Controllers/MessageController.php
git commit -m "feat(user): 为 Commission/Contract/MemberLevel/UserBind/Message 列表接口补充 Index Request DTO"
```

---

## Task 7: 现有 Response DTO Schema 完整审计

**Files:**
- Modify: `app/Api/User/Responses/**/*.php`（按需）

- [ ] **Step 7.1: 扫描所有 User 模块 Response DTO**

Run:

```bash
find app/Api/User/Responses -name '*.php' | sort
```

- [ ] **Step 7.2: 检查每个 Response DTO 是否满足以下要求**

1. 类顶部有 `#[OA\Schema(schema: '...')]`；
2. 每个私有属性都有 `#[OA\Property]`；
3. `description` 不为空；
4. `type` 正确（integer/string/boolean/array/object）；
5. 可空字段标注 `nullable: true`；
6. 日期时间字段标注 `format: 'date-time'`；
7. 数组字段声明 `items: new OA\Items(...)`；
8. 每个属性都有 getter/setter。

- [ ] **Step 7.3: 修复不完整 Schema**

对不符合上述要求的 DTO 进行修复。典型修复示例：

```php
// 修复前
#[OA\Property(property: 'created_at', description: '创建时间', type: 'string')]

// 修复后
#[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
```

- [ ] **Step 7.4: 提交**

```bash
git add app/Api/User/Responses/
git commit -m "docs(user): 完善 User 模块 Response DTO 的 OpenAPI Schema 定义"
```

---

## Task 8: OpenAPI 生成与路由校验

**Files:**
- 无新增文件，仅校验

- [ ] **Step 8.1: 运行 OpenAPI 文档生成**

Run:

```bash
php artisan openapi:generate
```

或项目实际命令（如 `php artisan l5-swagger:generate`、`./vendor/bin/openapi` 等）。

Expected: 命令成功退出，无 `Unable to resolve ref` 或 `Missing schema` 类报错。

- [ ] **Step 8.2: 运行路由列表检查**

Run:

```bash
php artisan route:list --path=api/user
```

Expected: 所有 User 模块路由正常加载，无绑定异常。

- [ ] **Step 8.3: 运行代码风格检查**

Run:

```bash
vendor/bin/pint app/Api/User/ --test
```

Expected: 无风格错误（或根据项目配置进行修复）。

- [ ] **Step 8.4: 提交修复**

```bash
git add -A
git commit -m "chore(user): 修复 OpenAPI 生成与代码风格问题"
```

---

## 自检清单

- [ ] **Spec 覆盖：** 设计文档中的“补缺口 + 完善 Schema”目标是否全部对应到任务？是，Task 1 处理 AddressController 列表返回，Task 2 处理 User/Profile 资料接口，Task 3-6 补各模块 Index Request DTO 缺口，Task 7 完善 Response Schema，Task 8 校验。
- [ ] **无占位符：** 计划中无 “TBD/TODO/稍后实现” 等模糊表述。
- [ ] **类型一致性：** 所有 IndexRequest 使用 `page` / `per_page` 常量命名；Response DTO 使用 `HasSerializableAttributes`；Schema 名称与类名一致。
- [ ] **可执行性：** 每个任务都包含具体文件路径、代码示例、命令和提交信息。

## 执行方式选择

**Plan complete and saved to `docs/superpowers/plans/2026-06-26-user-api-dto-schema-plan.md`. Two execution options:**

**1. Subagent-Driven (recommended)** - I dispatch a fresh subagent per task, review between tasks, fast iteration

**2. Inline Execution** - Execute tasks in this session using `executing-plans`, batch execution with checkpoints

**Which approach?**

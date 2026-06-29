# User 模块 Request/Response DTO Schema 补充设计

## 背景

`app/Api/` 下除 Admin 之外的模块（Common、Portal、Shop、Supplier、Seller、User）已经通过最近一次提交新增了大量 Request/Response DTO，但仍有部分控制器的接口返回原始模型数据，或部分 DTO 的 OpenAPI Schema 定义不够完整。本设计选择 **User 模块** 作为首批补齐对象，将其做成完整样板，再推广到其他模块。

## 目标

让 `app/Api/User/` 下所有控制器的每个公开方法都具备：

1. 完整的 `OA\RequestBody` / `OA\Response` Schema 引用；
2. 与之对应的、字段定义齐全的 Request / Response DTO；
3. 控制器返回数据时通过 DTO 序列化，而不是直接返回 Model/数组。

## 范围

仅处理 `app/Api/User/`，包括以下控制器：

- AddressController
- AuthController
- CartController
- CommissionController
- ComplaintController
- ConsentController
- ContractController
- CouponController
- DistributionController
- FavoriteController
- InvoiceController
- KycController
- MemberLevelController
- MessageController
- NotificationController
- OrderController
- OrderReviewController
- PointsController
- PrivacyController
- ProfileController
- RefundController
- SecurityController
- UserBindController
- UserController
- WalletController
- WithdrawController

## 执行步骤

1. **差距扫描**：逐个控制器核对方法签名、现有 DTO、返回数据形态，列出“缺 Request / 缺 Response / Schema 不完整”三类清单。
2. **补齐缺失 DTO**：
   - Request：继承 `Illuminate\Foundation\Http\FormRequest`，添加 `OA\Schema`、`rules()`、`messages()`、字段常量。
   - Response：使用 `Juling\Foundation\Support\Traits\HasSerializableAttributes`，添加 `OA\Schema`、`OA\Property`、getter/setter。
3. **完善现有 DTO Schema**：检查字段是否完整、类型/nullable/required/description/format 是否准确。
4. **更新控制器**：将 `Illuminate\Http\Request` 替换为具体 Request DTO，将原始 `response()->json(['data' => $model])` 替换为 DTO 包装。
5. **OpenAPI 生成校验**：运行文档生成命令，确认无报错、Schema 引用无 dangling ref。

## 命名与目录约定

| 类型 | 目录 | 命名示例 |
|------|------|----------|
| Request DTO | `app/Api/User/Requests/{Controller}/{Action}Request.php` | `AddressRequest`、`Order\OrderStoreRequest` |
| Response DTO | `app/Api/User/Responses/{Controller}/{Action}Response.php` | `AddressResponse`、`Order\OrderResponse` |
| 列表响应 | `app/Api/User/Responses/{Controller}/{Action}ListResponse.php` | `Order\OrderListResponse` |
| Schema 名称 | 与类名保持一致或符合模块前缀 | `AddressRequest`、`UserOrderResponse` |
| 字段常量 | `const string getFieldName = 'field_name';` | `const string getContactName = 'contact_name';` |

## 关键模式

### Request DTO 模板

```php
<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AddressStoreRequest',
    required: [self::getContactName, self::getContactPhone],
    properties: [
        new OA\Property(property: self::getContactName, description: '联系人姓名', type: 'string'),
        new OA\Property(property: self::getContactPhone, description: '联系人手机号', type: 'string'),
    ]
)]
class StoreRequest extends FormRequest
{
    const string getContactName = 'contact_name';
    const string getContactPhone = 'contact_phone';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getContactName => ['required', 'string', 'max:100'],
            self::getContactPhone => ['required', 'string', 'regex:/^1[3-9]\d{9}$/'],
        ];
    }
}
```

### Response DTO 模板

```php
<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Address;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AddressResponse')]
class AddressResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '地址ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contact_name', description: '联系人姓名', type: 'string')]
    private string $contactName;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function setContactName(string $contactName): void
    {
        $this->contactName = $contactName;
    }
}
```

### Controller 引用方式

```php
#[OA\Get(path: '/addresses', summary: '收货地址列表', security: [['bearerAuth' => []]], tags: ['会员中心'])]
#[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: AddressListResponse::class)))]
public function index(AddressIndexRequest $request): JsonResponse
{
    // ...
}
```

## 已知缺口（初步扫描）

- `AddressController::index` 返回 `$user->addresses()->...->get()`，缺少 `AddressListResponse`。
- `AddressController::show` 返回单个 Address 模型，可复用/新建 `AddressResponse`。
- `UserController::profile` 返回 `$user->load('addresses')`，缺少对应 Response DTO。
- `ProfileController::index` / `update` 返回原始 User 模型，可规范化为 `UserProfileResponse`。
- 部分现有 DTO 的 `OA\Property` 可能缺少 `nullable`、`required`、`format` 等属性，需在完善阶段补齐。

## 验收标准

- `app/Api/User/` 下所有控制器方法的 `OA\RequestBody` 和 `OA\Response` 都引用已存在的 DTO 类。
- 所有 Request DTO 都有完整的 `rules()` 和 `OA\Schema` 定义。
- 所有 Response DTO 都有完整的 `OA\Property` 和 getter/setter。
- 运行 OpenAPI 文档生成命令无报错。
- 代码风格与现有 DTO 保持一致（`declare(strict_types=1)`、字段常量、snake_case JSON key 映射等）。

## 后续计划

User 模块完成后，按同样模式依次处理 Seller、Supplier、Shop、Portal、Common 模块，最终覆盖 `app/Api/` 下除 Admin 之外的全部模块。

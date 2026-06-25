<?php

declare(strict_types=1);

/**
 * 生成 PHPMall B2B2C 领域模块骨架
 *
 * 运行：php scripts/generate-domain-modules.php
 */
$basePath = __DIR__.'/../app/Modules';

$modules = [
    [
        'name' => 'Auth',
        'title' => '认证授权域',
        'type' => '通用域',
        'summary' => '负责平台管理员、商家、子账号、分销员及 C 端用户的统一身份认证、RBAC 权限、数据范围控制与会话管理。',
        'aggregates' => ['UserAccount', 'Role', 'Permission', 'AuthToken', 'DataScope'],
    ],
    [
        'name' => 'Merchant',
        'title' => '商户域',
        'type' => '核心域',
        'summary' => '负责商家的全生命周期管理，包括入驻申请、资质审核、店铺开设、结算账户、违规冻结及退店结算。',
        'aggregates' => ['Merchant', 'MerchantApplication', 'MerchantQualification', 'MerchantSettlementAccount', 'MerchantFreezeRecord'],
    ],
    [
        'name' => 'Shop',
        'title' => '店铺域',
        'type' => '核心域',
        'summary' => '负责商家的前端经营载体，包括店铺信息、装修、分类、运费模板、营业状态及店铺评价。',
        'aggregates' => ['Shop', 'ShopCategory', 'ShopDecoration', 'FreightTemplate', 'ShopReview'],
    ],
    [
        'name' => 'Store',
        'title' => '门店域',
        'type' => '支撑域',
        'summary' => '负责 O2O 线下门店管理，包括门店信息、营业时间、自提点、配送范围及门店库存。',
        'aggregates' => ['Store', 'StoreBusinessHours', 'StorePickupPoint', 'StoreDeliveryRange', 'StoreInventory'],
    ],
    [
        'name' => 'Product',
        'title' => '商品域',
        'type' => '核心域',
        'summary' => '负责 SPU/SKU 商品模型、商品发布与审核、类目/品牌/属性管理、价格及上下架控制。',
        'aggregates' => ['Product', 'ProductSku', 'Category', 'Brand', 'ProductAttribute', 'ProductAuditRecord'],
    ],
    [
        'name' => 'Inventory',
        'title' => '库存域',
        'type' => '核心域',
        'summary' => '负责 SKU 库存扣减与释放、库存同步、多仓库存、库存流水及预占管理。',
        'aggregates' => ['Inventory', 'InventoryTransaction', 'Warehouse', 'InventoryReservation', 'InventorySnapshot'],
    ],
    [
        'name' => 'Order',
        'title' => '订单域',
        'type' => '核心域',
        'summary' => '负责购物车、订单创建、多商家拆单、订单状态机流转、发货、收货、评价等履约流程。',
        'aggregates' => ['Order', 'SubOrder', 'OrderItem', 'ShoppingCart', 'CartItem', 'OrderStatusLog', 'OrderReview'],
    ],
    [
        'name' => 'Payment',
        'title' => '支付域',
        'type' => '核心域',
        'summary' => '负责统一支付网关、支付订单创建与回调、多渠道支付、分账执行及支付对账。',
        'aggregates' => ['PaymentOrder', 'PaymentChannel', 'PaymentCallback', 'ProfitSharing', 'PlatformCommission'],
    ],
    [
        'name' => 'Refund',
        'title' => '退款售后域',
        'type' => '支撑域',
        'summary' => '负责退款/退货/换货申请、商家审核、退货物流、平台仲裁及资金原路退回。',
        'aggregates' => ['RefundRequest', 'ReturnLogistics', 'RefundAuditRecord', 'PlatformArbitration', 'RefundEvidence'],
    ],
    [
        'name' => 'Logistics',
        'title' => '物流域',
        'type' => '支撑域',
        'summary' => '负责发货单、物流单号、物流轨迹查询、包裹管理及电子面单。',
        'aggregates' => ['Shipment', 'DeliveryPackage', 'LogisticsTracking', 'LogisticsCompany', 'Waybill'],
    ],
    [
        'name' => 'Marketing',
        'title' => '营销域',
        'type' => '核心域',
        'summary' => '负责优惠券、满减满折、限时购、秒杀、积分商城等促销活动的创建、投放与核销。',
        'aggregates' => ['Coupon', 'CouponUsage', 'Promotion', 'SeckillActivity', 'SeckillItem', 'DiscountRule'],
    ],
    [
        'name' => 'Distribution',
        'title' => '分销域',
        'type' => '核心域',
        'summary' => '负责分销商关系树、三级分销限制、佣金比例配置、佣金结算与提现审核。',
        'aggregates' => ['Distributor', 'DistributorTree', 'CommissionRecord', 'CommissionSettlement', 'DistributionWithdraw'],
    ],
    [
        'name' => 'Finance',
        'title' => '财务域',
        'type' => '支撑域',
        'summary' => '负责虚拟钱包账户、余额/冻结金额、钱包流水、商家结算单、平台抽佣及财务对账。',
        'aggregates' => ['Wallet', 'WalletTransaction', 'SettlementOrder', 'ReconciliationBatch', 'ReconciliationRecord'],
    ],
    [
        'name' => 'Content',
        'title' => '内容域',
        'type' => '支撑域',
        'summary' => '负责平台运营内容，包括首页 Banner、公告、帮助中心、CMS 文章、协议及素材库。',
        'aggregates' => ['Banner', 'Article', 'Notice', 'HelpCenter', 'PlatformAgreement', 'MediaAsset'],
    ],
    [
        'name' => 'Notification',
        'title' => '通知域',
        'type' => '通用域',
        'summary' => '负责站内信、短信、邮件、APP 推送等消息通知的统一发送、模板管理与收件箱。',
        'aggregates' => ['Notification', 'NotificationTemplate', 'NotificationChannel', 'MessageInbox'],
    ],
    [
        'name' => 'System',
        'title' => '系统配置域',
        'type' => '通用域',
        'summary' => '负责全局参数配置、系统字典、平台协议、缓存策略、操作审计及系统监控。',
        'aggregates' => ['SystemConfig', 'ConfigGroup', 'Dictionary', 'OperationLog', 'AdminLoginLog'],
    ],
    [
        'name' => 'Supplier',
        'title' => '供应商域',
        'type' => '支撑域',
        'summary' => '负责供应链供应商的供货商品、采购订单、供货发货、库存同步及供货对账结算。',
        'aggregates' => ['Supplier', 'SupplyProduct', 'PurchaseOrder', 'SupplyInventory', 'SupplierSettlement'],
    ],
    [
        'name' => 'PlatformOperation',
        'title' => '平台运营域',
        'type' => '支撑域',
        'summary' => '负责平台层面的商家/商品/订单管理、入驻审核、强制下架、仲裁处理、数据仪表盘及运营任务。',
        'aggregates' => ['PlatformOperator', 'OperationTask', 'DashboardMetric', 'PlatformAuditRecord'],
    ],
    [
        'name' => 'Search',
        'title' => '搜索域',
        'type' => '支撑域',
        'summary' => '负责商品全文检索、关键词高亮、分类/品牌/属性过滤、价格区间聚合、排序及搜索建议。',
        'aggregates' => ['SearchIndex', 'SearchKeyword', 'SearchFilter', 'SearchSuggestion', 'SearchLog'],
    ],
];

$gitignore = "*\n!.gitignore\n";

function ensureDir(string $path): void
{
    if (! is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

function writeFile(string $path, string $content): void
{
    file_put_contents($path, $content);
}

$providers = [];

foreach ($modules as $module) {
    $name = $module['name'];
    $slug = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $name));
    $modulePath = $basePath.'/'.$name;

    $dirs = [
        'Database/factories',
        'Database/migrations',
        'Database/seeders',
        'Entities',
        'Http/Controllers',
        'Http/Middleware',
        'Http/Requests',
        'Http/Responses',
        'Models',
        'Providers',
        'Repositories',
        'Resources/Views',
        'Routes',
        'Services',
    ];

    foreach ($dirs as $dir) {
        $fullDir = $modulePath.'/'.$dir;
        ensureDir($fullDir);

        // 在空目录下放置 .gitignore
        if (in_array($dir, [
            'Database/factories',
            'Database/migrations',
            'Database/seeders',
            'Http/Controllers',
            'Http/Middleware',
            'Http/Requests',
            'Http/Responses',
            'Models',
            'Repositories',
            'Resources/Views',
            'Services',
        ], true)) {
            writeFile($fullDir.'/.gitignore', $gitignore);
        }
    }

    // README.md
    $aggregatesText = implode("\n- ", $module['aggregates']);
    $readme = <<<MD
# {$module['title']}（{$name}）

- **领域类型**：{$module['type']}
- **英文名称**：{$name}
- **职责**：{$module['summary']}

## 关键聚合根

- {$aggregatesText}

## 目录说明

| 目录 | 说明 |
|------|------|
| `Database` | 数据迁移、工厂、填充 |
| `Entities` | 领域实体 / 聚合根 |
| `Http` | 控制器、中间件、请求/响应 DTO |
| `Models` | Eloquent 模型 |
| `Providers` | 模块服务提供者 |
| `Repositories` | 仓储层 |
| `Resources` | 视图、语言包等资源 |
| `Routes` | 模块路由 |
| `Services` | 应用服务 / 领域服务 |

## 依赖领域

待补充。

## 设计备注

待补充。
MD;
    writeFile($modulePath.'/README.md', $readme);

    // ServiceProvider
    $provider = <<<PHP
<?php

declare(strict_types=1);

namespace App\\Modules\\{$name}\\Providers;

use Illuminate\\Support\\ServiceProvider;

class {$name}ServiceProvider extends ServiceProvider
{
    /**
     * Register any module services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any module services.
     */
    public function boot(): void
    {
        \$this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        \$this->loadViewsFrom(__DIR__ . '/../Resources/Views', '{$slug}');
        \$this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
}
PHP;
    writeFile($modulePath."/Providers/{$name}ServiceProvider.php", $provider);
    $providers[] = "App\\Modules\\{$name}\\Providers\\{$name}ServiceProvider::class";

    // Entity placeholder
    $entity = <<<PHP
<?php

declare(strict_types=1);

namespace App\\Modules\\{$name}\\Entities;

use Juling\\Foundation\\Support\\Traits\\HasSerializableAttributes;
use OpenApi\\Attributes as OA;

#[OA\\Schema(schema: '{$name}Entity')]
class {$name}Entity implements \\JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\\Property(property: 'id', description: 'ID', type: 'integer')]
    private int \$id;

    #[OA\\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string \$createdAt;

    #[OA\\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string \$updatedAt;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return \$this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int \$id): void
    {
        \$this->id = \$id;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return \$this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string \$createdAt): void
    {
        \$this->createdAt = \$createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return \$this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string \$updatedAt): void
    {
        \$this->updatedAt = \$updatedAt;
    }
}
PHP;
    writeFile($modulePath."/Entities/{$name}Entity.php", $entity);

    // Routes/web.php
    $route = <<<PHP
<?php

declare(strict_types=1);

use Illuminate\\Support\\Facades\\Route;

// {$module['title']}路由
// 请使用 gen:route 工具生成或手动补充
PHP;
    writeFile($modulePath.'/Routes/web.php', $route);

    echo "Generated module: {$name}\n";
}

// 输出生成的 Provider 列表，供手动注册使用
echo "\n// 请将以下 ServiceProvider 注册到 bootstrap/providers.php：\n";
echo implode(",\n", $providers)."\n";

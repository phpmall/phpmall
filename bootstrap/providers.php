<?php

use App\Modules\AuditLog\Providers\AuditLogServiceProvider;
use App\Modules\Auth\Providers\AuthServiceProvider;
use App\Modules\Compliance\Providers\ComplianceServiceProvider;
use App\Modules\ConsumerProtection\Providers\ConsumerProtectionServiceProvider;
use App\Modules\Content\Providers\ContentServiceProvider;
use App\Modules\Contract\Providers\ContractServiceProvider;
use App\Modules\DataPrivacy\Providers\DataPrivacyServiceProvider;
use App\Modules\Distribution\Providers\DistributionServiceProvider;
use App\Modules\Escrow\Providers\EscrowServiceProvider;
use App\Modules\Finance\Providers\FinanceServiceProvider;
use App\Modules\Inventory\Providers\InventoryServiceProvider;
use App\Modules\Invoice\Providers\InvoiceServiceProvider;
use App\Modules\Logistics\Providers\LogisticsServiceProvider;
use App\Modules\Marketing\Providers\MarketingServiceProvider;
use App\Modules\Merchant\Providers\MerchantServiceProvider;
use App\Modules\Notification\Providers\NotificationServiceProvider;
use App\Modules\Order\Providers\OrderServiceProvider;
use App\Modules\Payment\Providers\PaymentServiceProvider;
use App\Modules\PlatformOperation\Providers\PlatformOperationServiceProvider;
use App\Modules\Product\Providers\ProductServiceProvider;
use App\Modules\ProductCompliance\Providers\ProductComplianceServiceProvider;
use App\Modules\Refund\Providers\RefundServiceProvider;
use App\Modules\Search\Providers\SearchServiceProvider;
use App\Modules\Shop\Providers\ShopServiceProvider;
use App\Modules\Store\Providers\StoreServiceProvider;
use App\Modules\Supplier\Providers\SupplierServiceProvider;
use App\Modules\System\Providers\SystemServiceProvider;
use App\Modules\User\Providers\UserServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,

    // 通用域
    AuthServiceProvider::class,
    NotificationServiceProvider::class,
    SystemServiceProvider::class,
    AuditLogServiceProvider::class,

    // 核心域
    MerchantServiceProvider::class,
    ShopServiceProvider::class,
    ProductServiceProvider::class,
    InventoryServiceProvider::class,
    OrderServiceProvider::class,
    PaymentServiceProvider::class,
    MarketingServiceProvider::class,
    DistributionServiceProvider::class,

    // 合规核心域
    ComplianceServiceProvider::class,
    DataPrivacyServiceProvider::class,
    ProductComplianceServiceProvider::class,
    EscrowServiceProvider::class,

    // 支撑域
    StoreServiceProvider::class,
    RefundServiceProvider::class,
    LogisticsServiceProvider::class,
    FinanceServiceProvider::class,
    ContentServiceProvider::class,
    SupplierServiceProvider::class,
    PlatformOperationServiceProvider::class,
    SearchServiceProvider::class,

    // 合规支撑域
    InvoiceServiceProvider::class,
    ContractServiceProvider::class,
    ConsumerProtectionServiceProvider::class,

    // 已存在模块
    UserServiceProvider::class,
];

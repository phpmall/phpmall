<?php

declare(strict_types=1);

/**
 * 生成 PHPMall 财务/法务合规模块骨架
 *
 * 运行：php scripts/generate-compliance-modules.php
 */

$basePath = __DIR__ . '/../app/Modules';

$modules = [
    [
        'name' => 'Invoice',
        'title' => '发票税务域',
        'type' => '支撑域',
        'summary' => '负责电子发票（数电发票）开具、红冲/作废、发票与订单/结算单关联、纳税申报数据准备、分销佣金与商家提现的代扣代缴凭证管理。',
        'aggregates' => ['Invoice', 'InvoiceItem', 'InvoiceRedFlush', 'TaxWithholdingRecord', 'TaxReport'],
        'compliance' => [
            '《中华人民共和国发票管理办法》及数电发票推广政策',
            '《个人所得税法》代扣代缴义务',
            '增值税价税分离与申报',
            '退款触发红字信息表/红冲发票',
        ],
    ],
    [
        'name' => 'Compliance',
        'title' => '合规风控域',
        'type' => '核心域',
        'summary' => '负责 KYC 实名认证、客户风险等级评定、反洗钱（AML）可疑交易监测、制裁/PEP/红通名单筛查、反欺诈规则引擎及敏感操作二次校验。',
        'aggregates' => ['KycRecord', 'RiskProfile', 'SanctionScreening', 'SuspiciousTransactionReport', 'FraudRule'],
        'compliance' => [
            '《反洗钱法》及人民银行支付机构反洗钱规定',
            '《非金融机构支付服务管理办法》',
            '客户身份识别（KYC）与持续尽职调查',
            '可疑交易报告（STR）与大额交易监测',
        ],
    ],
    [
        'name' => 'DataPrivacy',
        'title' => '数据合规与隐私域',
        'type' => '核心域',
        'summary' => '负责用户协议与隐私政策版本管理、用户同意记录、数据主体权利（查阅/复制/更正/删除/注销/导出）、个人信息分类分级、未成年人保护及跨境传输评估。',
        'aggregates' => ['PrivacyPolicy', 'ConsentRecord', 'DataSubjectRequest', 'PersonalDataInventory', 'MinorProtectionRecord'],
        'compliance' => [
            '《个人信息保护法》（PIPL）',
            '《网络安全法》《数据安全法》',
            'GDPR 数据主体权利（如涉及出境）',
            '未成年人个人信息保护',
        ],
    ],
    [
        'name' => 'Contract',
        'title' => '电子合同与法务域',
        'type' => '支撑域',
        'summary' => '负责交易电子合同、商家入驻协议、合同模板管理、电子签名/时间戳、可信存证及合同履约状态跟踪，确保电子商务交易证据效力。',
        'aggregates' => ['Contract', 'ContractTemplate', 'ContractSignature', 'ContractEvidence', 'LegalDocument'],
        'compliance' => [
            '《电子商务法》电子合同与证据保全',
            '《电子签名法》',
            '《民法典》合同编',
            '平台服务协议与交易规则公示',
        ],
    ],
    [
        'name' => 'ConsumerProtection',
        'title' => '消费者权益与纠纷调解域',
        'type' => '支撑域',
        'summary' => '负责消费者投诉受理、平台调解、先行赔付、7 天无理由退货规则引擎、外部监管对接（12315/消协）及争议解决流程管理。',
        'aggregates' => ['ConsumerComplaint', 'MediationCase', 'CompensationOrder', 'ConsumerRightsRule', 'DisputeEvidence'],
        'compliance' => [
            '《消费者权益保护法》',
            '《电子商务法》争议解决与投诉处理',
            '7 天无理由退货与三包责任',
            '先行赔付与虚假广告退一赔三',
        ],
    ],
    [
        'name' => 'ProductCompliance',
        'title' => '商品合规与内容审核域',
        'type' => '核心域',
        'summary' => '负责违禁品识别、知识产权侵权投诉与申诉、虚假宣传/广告法审查、敏感内容审核、商家资质准入复核及违规处罚公示。',
        'aggregates' => ['ProhibitedProductRule', 'IpInfringementCase', 'AdContentReview', 'SensitiveWordLibrary', 'CompliancePenalty'],
        'compliance' => [
            '《电子商务法》平台对商家信息的审核义务',
            '《广告法》极限用语与虚假宣传',
            '《产品质量法》《食品安全法》',
            '知识产权"通知-删除"规则',
        ],
    ],
    [
        'name' => 'AuditLog',
        'title' => '审计日志域',
        'type' => '通用域',
        'summary' => '负责跨模块审计事件统一采集、WORM（一次写入不可修改）存储、审计查询与告警、合规报表及操作留痕生命周期管理。',
        'aggregates' => ['AuditEvent', 'AuditTrail', 'AuditReport', 'AuditRetentionPolicy', 'ImmutableLog'],
        'compliance' => [
            '等保 2.0 安全审计要求',
            '财务/支付操作留痕 ≥3 年',
            '敏感操作日志保留 180 天~2 年',
            '不可篡改审计证据链',
        ],
    ],
    [
        'name' => 'Escrow',
        'title' => '资金存管域',
        'type' => '核心域',
        'summary' => '负责平台交易资金存管、用户钱包资金隔离、商家待结算资金监管、保证金/押金管理，避免“大商户二清”合规风险。',
        'aggregates' => ['EscrowAccount', 'EscrowTransaction', 'MerchantDeposit', 'ConsumerDeposit', 'CustodyBankReconciliation'],
        'compliance' => [
            '人民银行《支付机构客户备付金集中存管办法》',
            '非金融机构支付服务管理办法（规避二清）',
            '平台自有资金与用户/商户资金隔离',
            '持牌支付机构“平台二级商户”或银行存管模式',
        ],
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
    $modulePath = $basePath . '/' . $name;

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
        $fullDir = $modulePath . '/' . $dir;
        ensureDir($fullDir);

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
            writeFile($fullDir . '/.gitignore', $gitignore);
        }
    }

    // 合规说明文本
    $complianceItems = implode("\n- ", $module['compliance']);
    $aggregatesText = implode("\n- ", $module['aggregates']);

    $readme = <<<MD
# {$module['title']}（{$name}）

- **领域类型**：{$module['type']}
- **英文名称**：{$name}
- **职责**：{$module['summary']}

## 关键聚合根

- {$aggregatesText}

## 合规依据

- {$complianceItems}

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
    writeFile($modulePath . '/README.md', $readme);

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
    writeFile($modulePath . "/Providers/{$name}ServiceProvider.php", $provider);
    $providers[] = "App\\Modules\\{$name}\\Providers\\{$name}ServiceProvider::class";

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
    writeFile($modulePath . "/Entities/{$name}Entity.php", $entity);

    $route = <<<PHP
<?php

declare(strict_types=1);

use Illuminate\\Support\\Facades\\Route;

// {$module['title']}路由
// 请使用 gen:route 工具生成或手动补充
PHP;
    writeFile($modulePath . '/Routes/web.php', $route);

    echo "Generated compliance module: {$name}\n";
}

echo "\n// 请将以下 ServiceProvider 注册到 bootstrap/providers.php：\n";
echo implode(",\n", $providers) . "\n";

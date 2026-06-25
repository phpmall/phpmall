<?php

declare(strict_types=1);

/**
 * 为现有领域模块 README 追加合规说明
 *
 * 运行：php scripts/sync-compliance-to-readmes.php
 */

$basePath = __DIR__ . '/../app/Modules';

$complianceNotes = [
    'Auth' => [
        '为支付、提现、商家资质审核等敏感操作提供身份认证与权限控制基础。',
        '应支持多因素认证（MFA）、密码复杂度策略、登录失败锁定。',
        '需与 Compliance 模块联动实现 KYC 实名认证与风险等级评定。',
    ],
    'Merchant' => [
        '商家入驻需审核营业执照、法人身份证、银行账户、特殊行业许可证。',
        '提现账户变更需二次验证与平台审核，防范资金被骗取。',
        '商家保证金、违规冻结、退店结算需与 Escrow/Finance 联动。',
        '资质到期前需提醒与复检，避免无证经营连带平台责任。',
    ],
    'Shop' => [
        '店铺信息、装修内容需符合广告法与平台规则。',
        '店铺评价不得删除/篡改，需配合 ConsumerProtection 反刷单治理。',
    ],
    'Store' => [
        'O2O 门店需符合当地经营许可与食品安全要求（如涉及餐饮）。',
        '自提点、配送范围信息变更需留痕。',
    ],
    'Product' => [
        '商品发布需接入 ProductCompliance 进行违禁品、侵权、虚假宣传审核。',
        '价格标注需符合价格法，避免原价/划线价欺诈。',
        '特殊商品（食品、医疗器械、化妆品等）需校验行业准入资质。',
    ],
    'Inventory' => [
        '库存流水需完整留痕，支持审计追踪。',
        '秒杀/营销库存扣减需防止超卖与套利。',
    ],
    'Order' => [
        '交易记录需保存 ≥3 年，满足电子商务法要求。',
        '订单成立时应触发 Contract 电子合同签署与存证。',
        '需内置 7 天无理由退货、三包责任等消费者权益规则触发点。',
    ],
    'Payment' => [
        '支付回调需验签、幂等，防止金额篡改。',
        '大额/可疑交易需上报 Compliance 模块进行 AML 监测。',
        '分账比例与资金路径需符合二清合规要求，优先通过 Escrow 存管。',
        '需生成电子回单作为用户/商家记账凭证。',
    ],
    'Refund' => [
        '退款应原路退回，金额不超过实付金额。',
        '退款时需联动 Invoice 模块触发发票红冲。',
        '售后证据需保全，支持平台仲裁与外部监管调阅。',
    ],
    'Logistics' => [
        '物流单号、轨迹需真实有效，防范虚假发货。',
        '电子面单需符合个人信息脱敏要求。',
    ],
    'Marketing' => [
        '优惠券、满减、秒杀规则需显著披露，避免价格欺诈。',
        '平台券与商家券的优惠承担方需在订单级明确分摊，用于开票与结算。',
        '广告位内容需接入 ProductCompliance 广告法审查。',
    ],
    'Distribution' => [
        '分销层级不得超过 3 级，禁止自购佣金，防范传销风险。',
        '分销佣金提现需由 Invoice 模块代扣代缴个人所得税。',
        '佣金冻结与结算周期需符合平台规则与税务要求。',
    ],
    'Finance' => [
        '虚拟钱包、商家待结算资金需接入 Escrow 进行专户存管。',
        '平台抽佣需价税分离，便于增值税申报。',
        '对账差异需及时处理并留痕，支持审计。',
        '需生成电子回单、结算单等财务凭证。',
    ],
    'Content' => [
        '平台协议、隐私政策、公告内容由 DataPrivacy 管理版本与同意记录。',
        'Banner、CMS、帮助中心内容需符合广告法与平台规则。',
    ],
    'Notification' => [
        '短信、邮件、推送需记录发送日志，支持用户退订与投诉。',
        '敏感通知（协议变更、资质审核结果）需确保送达并留痕。',
    ],
    'System' => [
        '基础操作审计日志建议升级至 AuditLog 模块统一治理。',
        '系统配置变更需留痕，支持合规审计。',
    ],
    'Supplier' => [
        '供应商入驻与供货商品需资质审核。',
        '采购/供货结算需开具发票并对账，对接 Invoice 模块。',
    ],
    'PlatformOperation' => [
        '强制下架、违规处罚、仲裁处理需有明确规则并留痕。',
        '运营任务与审核流程需与 ProductCompliance / ConsumerProtection 联动。',
        '数据仪表盘需包含合规指标（投诉率、违规率、对账差异率）。',
    ],
    'Search' => [
        '搜索排序需具备透明度与可解释性，防范大数据杀熟与自我优待。',
        '敏感词、违禁品搜索结果需过滤或提示。',
    ],
    'User' => [
        '用户注册需遵守 PIPL 最小必要原则，隐私政策需显著告知并记录同意。',
        '需支持用户查阅、复制、更正、删除、注销、导出个人数据。',
        '未成年人实名识别与消费限制需接入 DataPrivacy / Compliance。',
    ],
];

foreach ($complianceNotes as $module => $notes) {
    $readmePath = $basePath . '/' . $module . '/README.md';

    if (! file_exists($readmePath)) {
        echo "Skip {$module}: README.md not found\n";
        continue;
    }

    $content = file_get_contents($readmePath);

    if (str_contains($content, '## 合规说明')) {
        echo "Skip {$module}: compliance section already exists\n";
        continue;
    }

    $items = implode("\n- ", $notes);
    $append = "\n\n## 合规说明\n\n- {$items}\n";

    file_put_contents($readmePath, $content . $append);

    echo "Updated {$module} README with compliance notes\n";
}

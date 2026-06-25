# 合规风控域（Compliance）

- **领域类型**：核心域
- **英文名称**：Compliance
- **职责**：负责 KYC 实名认证、客户风险等级评定、反洗钱（AML）可疑交易监测、制裁/PEP/红通名单筛查、反欺诈规则引擎及敏感操作二次校验。

## 关键聚合根

- KycRecord
- RiskProfile
- SanctionScreening
- SuspiciousTransactionReport
- FraudRule

## 合规依据

- 《反洗钱法》及人民银行支付机构反洗钱规定
- 《非金融机构支付服务管理办法》
- 客户身份识别（KYC）与持续尽职调查
- 可疑交易报告（STR）与大额交易监测

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
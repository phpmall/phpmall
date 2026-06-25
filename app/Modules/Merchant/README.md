# 商户域（Merchant）

- **领域类型**：核心域
- **英文名称**：Merchant
- **职责**：负责商家的全生命周期管理，包括入驻申请、资质审核、店铺开设、结算账户、违规冻结及退店结算。

## 关键聚合根

- Merchant
- MerchantApplication
- MerchantQualification
- MerchantSettlementAccount
- MerchantFreezeRecord

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

## 合规说明

- 商家入驻需审核营业执照、法人身份证、银行账户、特殊行业许可证。
- 提现账户变更需二次验证与平台审核，防范资金被骗取。
- 商家保证金、违规冻结、退店结算需与 Escrow/Finance 联动。
- 资质到期前需提醒与复检，避免无证经营连带平台责任。

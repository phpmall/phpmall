# 财务域（Finance）

- **领域类型**：支撑域
- **英文名称**：Finance
- **职责**：负责虚拟钱包账户、余额/冻结金额、钱包流水、商家结算单、平台抽佣及财务对账。

## 关键聚合根

- Wallet
- WalletTransaction
- SettlementOrder
- ReconciliationBatch
- ReconciliationRecord

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

- 虚拟钱包、商家待结算资金需接入 Escrow 进行专户存管。
- 平台抽佣需价税分离，便于增值税申报。
- 对账差异需及时处理并留痕，支持审计。
- 需生成电子回单、结算单等财务凭证。

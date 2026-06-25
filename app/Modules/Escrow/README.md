# 资金存管域（Escrow）

- **领域类型**：核心域
- **英文名称**：Escrow
- **职责**：负责平台交易资金存管、用户钱包资金隔离、商家待结算资金监管、保证金/押金管理，避免“大商户二清”合规风险。

## 关键聚合根

- EscrowAccount
- EscrowTransaction
- MerchantDeposit
- ConsumerDeposit
- CustodyBankReconciliation

## 合规依据

- 人民银行《支付机构客户备付金集中存管办法》
- 非金融机构支付服务管理办法（规避二清）
- 平台自有资金与用户/商户资金隔离
- 持牌支付机构“平台二级商户”或银行存管模式

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
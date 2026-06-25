# 退款售后域（Refund）

- **领域类型**：支撑域
- **英文名称**：Refund
- **职责**：负责退款/退货/换货申请、商家审核、退货物流、平台仲裁及资金原路退回。

## 关键聚合根

- RefundRequest
- ReturnLogistics
- RefundAuditRecord
- PlatformArbitration
- RefundEvidence

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

- 退款应原路退回，金额不超过实付金额。
- 退款时需联动 Invoice 模块触发发票红冲。
- 售后证据需保全，支持平台仲裁与外部监管调阅。

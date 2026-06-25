# 发票税务域（Invoice）

- **领域类型**：支撑域
- **英文名称**：Invoice
- **职责**：负责电子发票（数电发票）开具、红冲/作废、发票与订单/结算单关联、纳税申报数据准备、分销佣金与商家提现的代扣代缴凭证管理。

## 关键聚合根

- Invoice
- InvoiceItem
- InvoiceRedFlush
- TaxWithholdingRecord
- TaxReport

## 合规依据

- 《中华人民共和国发票管理办法》及数电发票推广政策
- 《个人所得税法》代扣代缴义务
- 增值税价税分离与申报
- 退款触发红字信息表/红冲发票

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
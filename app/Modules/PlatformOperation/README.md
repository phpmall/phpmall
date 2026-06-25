# 平台运营域（PlatformOperation）

- **领域类型**：支撑域
- **英文名称**：PlatformOperation
- **职责**：负责平台层面的商家/商品/订单管理、入驻审核、强制下架、仲裁处理、数据仪表盘及运营任务。

## 关键聚合根

- PlatformOperator
- OperationTask
- DashboardMetric
- PlatformAuditRecord

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

- 强制下架、违规处罚、仲裁处理需有明确规则并留痕。
- 运营任务与审核流程需与 ProductCompliance / ConsumerProtection 联动。
- 数据仪表盘需包含合规指标（投诉率、违规率、对账差异率）。

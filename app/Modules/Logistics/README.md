# 物流域（Logistics）

- **领域类型**：支撑域
- **英文名称**：Logistics
- **职责**：负责发货单、物流单号、物流轨迹查询、包裹管理及电子面单。

## 关键聚合根

- Shipment
- DeliveryPackage
- LogisticsTracking
- LogisticsCompany
- Waybill

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

- 物流单号、轨迹需真实有效，防范虚假发货。
- 电子面单需符合个人信息脱敏要求。

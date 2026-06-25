# 门店域（Store）

- **领域类型**：支撑域
- **英文名称**：Store
- **职责**：负责 O2O 线下门店管理，包括门店信息、营业时间、自提点、配送范围及门店库存。

## 关键聚合根

- Store
- StoreBusinessHours
- StorePickupPoint
- StoreDeliveryRange
- StoreInventory

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

- O2O 门店需符合当地经营许可与食品安全要求（如涉及餐饮）。
- 自提点、配送范围信息变更需留痕。

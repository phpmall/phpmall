# 库存域（Inventory）

- **领域类型**：核心域
- **英文名称**：Inventory
- **职责**：负责 SKU 库存扣减与释放、库存同步、多仓库存、库存流水及预占管理。

## 关键聚合根

- Inventory
- InventoryTransaction
- Warehouse
- InventoryReservation
- InventorySnapshot

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

- 库存流水需完整留痕，支持审计追踪。
- 秒杀/营销库存扣减需防止超卖与套利。

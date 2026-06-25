# 供应商域（Supplier）

- **领域类型**：支撑域
- **英文名称**：Supplier
- **职责**：负责供应链供应商的供货商品、采购订单、供货发货、库存同步及供货对账结算。

## 关键聚合根

- Supplier
- SupplyProduct
- PurchaseOrder
- SupplyInventory
- SupplierSettlement

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

- 供应商入驻与供货商品需资质审核。
- 采购/供货结算需开具发票并对账，对接 Invoice 模块。

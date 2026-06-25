# 订单域（Order）

- **领域类型**：核心域
- **英文名称**：Order
- **职责**：负责购物车、订单创建、多商家拆单、订单状态机流转、发货、收货、评价等履约流程。

## 关键聚合根

- Order
- SubOrder
- OrderItem
- ShoppingCart
- CartItem
- OrderStatusLog
- OrderReview

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

- 交易记录需保存 ≥3 年，满足电子商务法要求。
- 订单成立时应触发 Contract 电子合同签署与存证。
- 需内置 7 天无理由退货、三包责任等消费者权益规则触发点。

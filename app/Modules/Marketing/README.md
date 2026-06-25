# 营销域（Marketing）

- **领域类型**：核心域
- **英文名称**：Marketing
- **职责**：负责优惠券、满减满折、限时购、秒杀、积分商城等促销活动的创建、投放与核销。

## 关键聚合根

- Coupon
- CouponUsage
- Promotion
- SeckillActivity
- SeckillItem
- DiscountRule

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

- 优惠券、满减、秒杀规则需显著披露，避免价格欺诈。
- 平台券与商家券的优惠承担方需在订单级明确分摊，用于开票与结算。
- 广告位内容需接入 ProductCompliance 广告法审查。

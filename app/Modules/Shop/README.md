# 店铺域（Shop）

- **领域类型**：核心域
- **英文名称**：Shop
- **职责**：负责商家的前端经营载体，包括店铺信息、装修、分类、运费模板、营业状态及店铺评价。

## 关键聚合根

- Shop
- ShopCategory
- ShopDecoration
- FreightTemplate
- ShopReview

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

- 店铺信息、装修内容需符合广告法与平台规则。
- 店铺评价不得删除/篡改，需配合 ConsumerProtection 反刷单治理。

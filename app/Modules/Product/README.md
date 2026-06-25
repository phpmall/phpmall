# 商品域（Product）

- **领域类型**：核心域
- **英文名称**：Product
- **职责**：负责 SPU/SKU 商品模型、商品发布与审核、类目/品牌/属性管理、价格及上下架控制。

## 关键聚合根

- Product
- ProductSku
- Category
- Brand
- ProductAttribute
- ProductAuditRecord

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

- 商品发布需接入 ProductCompliance 进行违禁品、侵权、虚假宣传审核。
- 价格标注需符合价格法，避免原价/划线价欺诈。
- 特殊商品（食品、医疗器械、化妆品等）需校验行业准入资质。

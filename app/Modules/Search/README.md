# 搜索域（Search）

- **领域类型**：支撑域
- **英文名称**：Search
- **职责**：负责商品全文检索、关键词高亮、分类/品牌/属性过滤、价格区间聚合、排序及搜索建议。

## 关键聚合根

- SearchIndex
- SearchKeyword
- SearchFilter
- SearchSuggestion
- SearchLog

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

- 搜索排序需具备透明度与可解释性，防范大数据杀熟与自我优待。
- 敏感词、违禁品搜索结果需过滤或提示。

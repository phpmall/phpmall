# 商品合规与内容审核域（ProductCompliance）

- **领域类型**：核心域
- **英文名称**：ProductCompliance
- **职责**：负责违禁品识别、知识产权侵权投诉与申诉、虚假宣传/广告法审查、敏感内容审核、商家资质准入复核及违规处罚公示。

## 关键聚合根

- ProhibitedProductRule
- IpInfringementCase
- AdContentReview
- SensitiveWordLibrary
- CompliancePenalty

## 合规依据

- 《电子商务法》平台对商家信息的审核义务
- 《广告法》极限用语与虚假宣传
- 《产品质量法》《食品安全法》
- 知识产权"通知-删除"规则

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
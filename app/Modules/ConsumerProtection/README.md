# 消费者权益与纠纷调解域（ConsumerProtection）

- **领域类型**：支撑域
- **英文名称**：ConsumerProtection
- **职责**：负责消费者投诉受理、平台调解、先行赔付、7 天无理由退货规则引擎、外部监管对接（12315/消协）及争议解决流程管理。

## 关键聚合根

- ConsumerComplaint
- MediationCase
- CompensationOrder
- ConsumerRightsRule
- DisputeEvidence

## 合规依据

- 《消费者权益保护法》
- 《电子商务法》争议解决与投诉处理
- 7 天无理由退货与三包责任
- 先行赔付与虚假广告退一赔三

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
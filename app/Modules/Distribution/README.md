# 分销域（Distribution）

- **领域类型**：核心域
- **英文名称**：Distribution
- **职责**：负责分销商关系树、三级分销限制、佣金比例配置、佣金结算与提现审核。

## 关键聚合根

- Distributor
- DistributorTree
- CommissionRecord
- CommissionSettlement
- DistributionWithdraw

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

- 分销层级不得超过 3 级，禁止自购佣金，防范传销风险。
- 分销佣金提现需由 Invoice 模块代扣代缴个人所得税。
- 佣金冻结与结算周期需符合平台规则与税务要求。

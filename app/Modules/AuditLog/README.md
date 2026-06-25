# 审计日志域（AuditLog）

- **领域类型**：通用域
- **英文名称**：AuditLog
- **职责**：负责跨模块审计事件统一采集、WORM（一次写入不可修改）存储、审计查询与告警、合规报表及操作留痕生命周期管理。

## 关键聚合根

- AuditEvent
- AuditTrail
- AuditReport
- AuditRetentionPolicy
- ImmutableLog

## 合规依据

- 等保 2.0 安全审计要求
- 财务/支付操作留痕 ≥3 年
- 敏感操作日志保留 180 天~2 年
- 不可篡改审计证据链

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
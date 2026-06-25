# 通知域（Notification）

- **领域类型**：通用域
- **英文名称**：Notification
- **职责**：负责站内信、短信、邮件、APP 推送等消息通知的统一发送、模板管理与收件箱。

## 关键聚合根

- Notification
- NotificationTemplate
- NotificationChannel
- MessageInbox

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

- 短信、邮件、推送需记录发送日志，支持用户退订与投诉。
- 敏感通知（协议变更、资质审核结果）需确保送达并留痕。

# 认证授权域（Auth）

- **领域类型**：通用域
- **英文名称**：Auth
- **职责**：负责平台管理员、商家、子账号、分销员及 C 端用户的统一身份认证、RBAC 权限、数据范围控制与会话管理。

## 关键聚合根

- UserAccount
- Role
- Permission
- AuthToken
- DataScope

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

- 为支付、提现、商家资质审核等敏感操作提供身份认证与权限控制基础。
- 应支持多因素认证（MFA）、密码复杂度策略、登录失败锁定。
- 需与 Compliance 模块联动实现 KYC 实名认证与风险等级评定。

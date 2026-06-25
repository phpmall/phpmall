# 用户域（User）

- **领域类型**：支撑域
- **英文名称**：User
- **职责**：负责 C 端用户注册登录、个人资料、收货地址、会员等级、第三方绑定等用户资产管理。

## 关键聚合根

- User（用户）
- UserProfile（用户资料）
- UserAddress（收货地址）
- MemberLevel（会员等级）
- UserBind（第三方账号绑定）

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

## 合规说明

- 用户注册需遵守 PIPL 最小必要原则，隐私政策需显著告知并记录同意。
- 需支持用户查阅、复制、更正、删除、注销、导出个人数据。
- 未成年人实名识别与消费限制需接入 DataPrivacy / Compliance。

## 依赖领域

- `Auth`：身份认证与 RBAC
- `DataPrivacy`：隐私政策、用户同意、数据主体权利
- `Compliance`：实名认证、未成年人保护

## 设计备注

当前模块为基础 CRUD 脚手架，建议逐步扩展为完整的会员域（Member），纳入实名认证、隐私设置、数据导出/注销等合规能力。

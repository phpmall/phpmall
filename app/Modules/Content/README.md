# 内容域（Content）

- **领域类型**：支撑域
- **英文名称**：Content
- **职责**：负责平台运营内容，包括首页 Banner、公告、帮助中心、CMS 文章、协议及素材库。

## 关键聚合根

- Banner
- Article
- Notice
- HelpCenter
- PlatformAgreement
- MediaAsset

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

- 平台协议、隐私政策、公告内容由 DataPrivacy 管理版本与同意记录。
- Banner、CMS、帮助中心内容需符合广告法与平台规则。

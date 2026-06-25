# 数据合规与隐私域（DataPrivacy）

- **领域类型**：核心域
- **英文名称**：DataPrivacy
- **职责**：负责用户协议与隐私政策版本管理、用户同意记录、数据主体权利（查阅/复制/更正/删除/注销/导出）、个人信息分类分级、未成年人保护及跨境传输评估。

## 关键聚合根

- PrivacyPolicy
- ConsentRecord
- DataSubjectRequest
- PersonalDataInventory
- MinorProtectionRecord

## 合规依据

- 《个人信息保护法》（PIPL）
- 《网络安全法》《数据安全法》
- GDPR 数据主体权利（如涉及出境）
- 未成年人个人信息保护

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
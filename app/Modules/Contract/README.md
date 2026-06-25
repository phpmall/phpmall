# 电子合同与法务域（Contract）

- **领域类型**：支撑域
- **英文名称**：Contract
- **职责**：负责交易电子合同、商家入驻协议、合同模板管理、电子签名/时间戳、可信存证及合同履约状态跟踪，确保电子商务交易证据效力。

## 关键聚合根

- Contract
- ContractTemplate
- ContractSignature
- ContractEvidence
- LegalDocument

## 合规依据

- 《电子商务法》电子合同与证据保全
- 《电子签名法》
- 《民法典》合同编
- 平台服务协议与交易规则公示

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
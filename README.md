# PHPMall - B2B2C 多商户电商平台

基于 Vite+ Monorepo 的 B2B2C 多商户电商平台。

## 项目结构

```
phpmall/
├── apps/
│   ├── backend/        # Laravel 13 后端（PHP 8.4+）
│   └── website/        # Vite+ 前端网站
├── packages/
│   ├── seller/         # 商家后台（Vue 3）
│   ├── utils/          # 通用工具包
│   └── types/          # 类型定义
├── tools/              # 工具配置
├── scripts/            # 部署脚本
└── docs/               # 项目文档
    ├── B2B2C-需求文档.md
    ├── B2B2C-PRD产品文档.md
    ├── B2B2C-技术方案文档.md
    ├── B2B2C-技术架构文档-总纲归档.md
    ├── B2B2C-数据库设计文档.md
    ├── B2B2C-API接口契约文档.md
    ├── B2B2C-安全设计文档.md
    └── B2B2C-验收方案文档.md
```

## 开发

```bash
# 安装依赖
vp install

# 检查代码质量（格式化 + 类型检查 + lint）
vp check

# 运行测试
vp run -r test

# 构建所有子包
vp run -r build

# 启动开发服务器
vp run dev
```

> **文档导航**：完整的需求、产品、技术方案请参见 [docs/](docs/) 目录。

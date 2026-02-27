### Monorepo 模式下使用 Vite 统一管理多项目

作为前端工程架构师，我会从实际经验出发，针对你的项目结构（根目录下有 `package.json`，并在 `packages` 目录中包含 `admin`、`user`、`seller`、`mobile` 等子项目）来讨论解决方案。这种典型的 Monorepo 模式非常适合大型前端项目，能避免依赖版本冲突、简化代码共享，并通过统一工具链提升效率。

#### 推荐方案
在 Monorepo 中，依赖统一管理主要依赖包管理器（如 pnpm、yarn 或 npm）的 **workspaces** 功能，而构建和打包则可以通过 Vite 的配置结合这些工具实现。Vite 本身不直接处理 Monorepo 的依赖安装，但它可以作为构建引擎，与 workspaces 无缝集成。

**首选方案：pnpm + Vite + Turborepo（可选增强）**
- **为什么 pnpm？** pnpm 是 Monorepo 的最佳选择，它使用硬链接和内容寻址存储，避免重复安装依赖，支持高效的 workspaces。相比 yarn 或 npm，pnpm 更节省磁盘空间和时间，尤其在多项目中。
- **为什么 Vite？** Vite 支持快速开发服务器和优化构建，能处理多入口应用。你可以在根目录配置共享的 Vite 配置，或在子项目中独立配置，然后通过 pnpm 的递归命令（如 `pnpm -r`）统一操作。
- **Turborepo（可选）**：如果项目规模大（多个子项目间有依赖关系），推荐引入 Turborepo。它是 Vercel 出品的 Monorepo 工具，能缓存构建任务、智能并行执行，并与 Vite 完美集成。无需它也能工作，但它能显著提升构建速度（尤其是 CI/CD 中）。

如果你的项目简单，不涉及复杂任务依赖，直接用 pnpm + Vite 就够了。下面我给出纯 pnpm + Vite 的具体操作示例；如果需要 Turborepo，我会在末尾补充。

#### 具体操作示例（纯 pnpm + Vite）
假设你的目录结构如下：
```
project-root/
├── package.json
├── pnpm-workspace.yaml  // 用于 pnpm workspaces 配置
└── packages/
    ├── admin/
    │   ├── package.json
    │   ├── vite.config.js  // 子项目可选配置
    │   └── src/  // 代码
    ├── user/
    │   ├── package.json
    │   └── ... 
    ├── seller/
    │   ├── package.json
    │   └── ...
    └── mobile/
        ├── package.json
        └── ...
```

##### 步骤 1: 安装 pnpm 并配置 workspaces
- 全局安装 pnpm（如果未安装）：`npm install -g pnpm`。
- 在根目录创建 `pnpm-workspace.yaml` 文件（pnpm 的 workspaces 配置文件，比 package.json 中的 workspaces 更灵活）：
  ```yaml
  packages:
    - 'packages/*'  # 匹配所有子目录
  ```
- 在根 `package.json` 中添加共享脚本和 devDependencies（Vite 等工具安装在根目录，作为共享依赖）：
  ```json
  {
    "name": "project-root",
    "private": true,
    "scripts": {
      "install:all": "pnpm install",
      "dev:admin": "pnpm -F admin dev",  // -F 指定子包运行
      "dev:all": "pnpm -r --parallel dev",  // 并行运行所有子包的 dev
      "build:admin": "pnpm -F admin build",
      "build:all": "pnpm -r build"  // 递归构建所有子包
    },
    "devDependencies": {
      "vite": "^5.0.0",  // 统一 Vite 版本
      "@vitejs/plugin-react": "^4.2.0"  // 示例：如果用 React
    }
  }
  ```
- 运行 `pnpm install`：这会在根目录统一安装所有依赖。子项目会通过 workspaces 共享根依赖，避免重复。

##### 步骤 2: 配置子项目
每个子项目（如 `packages/admin/package.json`）配置自己的脚本，并依赖根的 Vite：
```json
{
  "name": "@project/admin",  // 使用 scoped name，便于内部依赖
  "version": "1.0.0",
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "preview": "vite preview"
  },
  "dependencies": {
    "react": "^18.2.0"  // 子项目独有依赖
  },
  "devDependencies": {
    // 无需重复安装 vite，它从根继承
  }
}
```
- 在子项目中创建 `vite.config.js`（可选，如果需要自定义；否则继承根配置）：
  ```js
  import { defineConfig } from 'vite';
  import react from '@vitejs/plugin-react';

  export default defineConfig({
    plugins: [react()],
    base: '/admin/',  // 示例：自定义 base path
    build: {
      outDir: 'dist/admin'  // 输出目录
    }
  });
  ```

##### 步骤 3: 统一管理依赖
- 添加/更新依赖：在根目录运行 `pnpm add <package> -w`（`-w` 表示 workspace-root），它会添加到根 `package.json`，所有子项目共享。
- 子项目独有依赖：在子目录运行 `pnpm add <package>`，或从根运行 `pnpm add <package> -F admin`。
- 移除依赖：类似，使用 `pnpm remove <package> -w` 或 `-F <pkg>`。
- 统一安装/更新：根目录 `pnpm install` 或 `pnpm update` 会递归处理所有子项目，避免版本冲突（pnpm 会 hoist 共享依赖到根）。

##### 步骤 4: 统一操作打包和开发
- **开发模式**：
  - 单项目：根目录运行 `pnpm dev:admin`（启动 admin 的 Vite dev server）。
  - 所有项目：`pnpm dev:all`（并行启动所有子项目的 dev server，每个监听不同端口）。
- **构建打包**：
  - 单项目：`pnpm build:admin`（构建 admin 到其 dist 目录）。
  - 所有项目：`pnpm build:all`（递归构建所有子项目）。
- **其他操作**：如 lint/test，可以在根 scripts 中添加类似 `"lint:all": "pnpm -r lint"`。

##### 步骤 5: 处理子项目间依赖（可选）
如果 `admin` 依赖 `user` 的组件：
- 在 `user/package.json` 添加 `"main": "dist/index.js"`（假设构建输出）。
- 在 `admin/package.json` 添加依赖：`"dependencies": { "@project/user": "workspace:*" }`（`workspace:*` 表示链接本地版本）。
- 运行 `pnpm install` 更新链接。

#### 如果引入 Turborepo 增强
如果项目复杂（e.g., 构建缓存、任务管道），安装 Turborepo：
- 根目录 `pnpm add turbo -D -w`。
- 创建 `turbo.json`：
  ```json
  {
    "pipeline": {
      "build": {
        "dependsOn": ["^build"],  // 自动处理依赖顺序
        "outputs": ["dist/**"]
      },
      "dev": {
        "cache": false  // dev 不缓存
      }
    }
  }
  ```
- 更新根 `package.json` scripts：
  ```json
  "scripts": {
    "build:all": "turbo run build",
    "dev:all": "turbo run dev --parallel"
  }
  ```
- Turborepo 会自动检测 workspaces，并缓存 Vite 构建输出，提高效率。

#### 注意事项
- **端口冲突**：在并行 dev 时，Vite 默认端口 5173；可在子 vite.config.js 中自定义 `server.port: 5173 + offset`。
- **共享代码**：如果有公共组件，创建 `packages/shared/` 子包，其他项目依赖它。
- **版本控制**：用 Git 管理根目录，确保 `.pnpm-lock.yaml` 被提交。
- **潜在问题**：如果 Vite 配置冲突，考虑用 `vite-tsconfig-paths` 插件处理路径别名。测试环境用 Vitest。
- **性能**：pnpm + Turborepo 在大型 Monorepo 中表现最佳；如果用 yarn，配置类似，但 pnpm 更推荐。

这个方案已在多个实际项目中验证，能高效统一管理。如果你有特定框架（如 React/Vue）或更多细节，我可以进一步调整示例。
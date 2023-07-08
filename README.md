# PHPMall Project 🏝️

> 👷 Under development. Releasing soon.

High performance e-commerce platform for PHP based on Octane.

> 运行环境要求 PHP8.1。

目标：打造千万级数据的在线交易平台系统，保证初创企业初期业务数据支撑。

### 演示地址

- 商城首页：https://www.phpmall.net
- 认证平台：https://passport.phpmall.net
- 运营平台：https://console.phpmall.net
- 供应平台：https://supplier.phpmall.net
- 卖家平台：https://seller.phpmall.net
- 买家平台：https://home.phpmall.net
- 微商城：https://m.phpmall.net

### 创建（克隆）项目

```
git clone https://gitee.com/phpmall/phpmall.git
```

### 安装前端工程依赖

```
# 商城首页
cd phpmall-web
pnpm install
pnpm run build-only

# 认证平台
cd phpmall-passport
pnpm install
pnpm run build-only

# 运营平台
cd phpmall-admin
pnpm install
pnpm run build-only

# 供应平台
cd phpmall-supplier
pnpm install
pnpm run build-only

# 卖家平台
cd phpmall-seller
pnpm install
pnpm run build-only

# 买家平台
cd phpmall-user
pnpm install
pnpm run build-only

# 微商城
cd phpmall-mobile
pnpm install
pnpm run build:h5
```

### 安装后端工程依赖

```
cd phpmall-server
composer config -g repos.packagist composer https://packagist.pages.dev
composer install -o
cp .env.example .env
php artisan key:generate
```

### 创建数据库

```
CREATE DATABASE `phpmall` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci';
```

### 配置数据库连接

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=phpmall
DB_USERNAME=root
DB_PASSWORD=
```

### 执行数据库迁移

```
php artisan migrate
```

### 测试运行

```
php artisan serve
```

在浏览器中输入地址：

http://localhost:8000/

### License

Apache-2.0

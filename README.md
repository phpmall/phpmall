# PHPMall Project 🏝️

> 👷 Under development. Releasing soon.

High performance e-commerce platform for PHP based on Octane.

> 运行环境要求PHP8.1

目标：打造千万级数据的在线交易平台系统，保证初创企业初期业务数据支撑。

## 演示地址

- 商城首页：https://demo.phpmall.net
- 运营平台：https://demo.phpmall.net/admin
- 认证平台：https://demo.phpmall.net/passport
- 供应平台：https://demo.phpmall.net/supplier
- 卖家平台：https://demo.phpmall.net/seller
- 买家平台：https://demo.phpmall.net/home
- 微商城：https://demo.phpmall.net/mobile

## 安装

```
composer create-project phpmall/phpmall
```

## 安装前端工程依赖

```
# 商城首页
pnpm install
pnpm run build

# 微商城
cd mobile
pnpm install
pnpm run build:h5
```

## 安装后端工程依赖

```
composer config -g repos.packagist composer https://packagist.pages.dev
composer install -o
cp .env.example .env
php artisan key:generate
```

## 创建数据库

```
CREATE DATABASE `phpmall` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci';
```

## 数据库配置

编辑 .env 文件，修改数据库连接信息：

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=phpmall
DB_USERNAME=root
DB_PASSWORD=
```

## 数据迁移及填充

```
php artisan migrate
php artisan db:seed
```

## 运行

现在只需要做最后一步来验证是否正常运行。

进入命令行下面，执行下面指令

```
php artisan serve
```

在浏览器中输入地址：

http://localhost:8000/

## 版权信息

Apache2开源协议，并提供免费使用。

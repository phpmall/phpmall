# PHPMall Project 🏝️

> 👷 Under development. Releasing soon.

High performance e-commerce platform for PHP based on Octane.

> 运行环境要求 PHP8.1。

目标：打造千万级数据的在线交易平台系统，保证初创企业初期业务数据支撑。

### 演示地址

- 商城首页：https://demo.phpmall.net/
- 运营平台：https://demo.phpmall.net/admin/
- 卖家平台：https://demo.phpmall.net/seller/
- 买家平台：https://demo.phpmall.net/user/
- 微商城：https://demo.phpmall.net/mobile/

### 创建项目

```
composer config -g repos.packagist composer https://packagist.pages.dev
composer create-project phpmall/phpmall
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

# PHPMall Project 🏝️

> 👷 Under development. Releasing soon.

High performance e-commerce platform for PHP based on Octane.

目标：打造千万级数据的在线交易平台系统，保证初创企业初期业务数据支撑。

## 演示地址

- 商城首页：https://demo.phpmall.net
- 运营中心：https://demo.phpmall.net/admin
- 卖家中心：https://demo.phpmall.net/seller
- 买家中心：https://demo.phpmall.net/user
- 微商城：https://demo.phpmall.net/mobile

## 开发环境

### 安装 MySQL

```cmd
docker run -d --name mysql -p 3306:3306 -v %cd%/docker/mysql/data:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=root mysql:8.0.36 --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci
```

### 安装 Redis

```cmd
docker run -d --name redis -p 6379:6379 redis:7.2 redis-server --save 60 1 --loglevel warning
```

### Web 开发环境

```cmd
docker build -t apache-php8.2 docker/dev
docker run --rm -d --name phpmall -v %cd%:/var/www/html -v %cd%/docker/dev/conf:/etc/apache2/sites-enabled -p 8000:80 apache-php8.2
```

注意在`cmd`模式下运行以上代码。

### WSL2 环境

推荐参考 PHPUnit [部署文档](https://docs.phpunit.de/en/10.0/installation.html)，基于 Debian 快速搭建开发环境。

```shell
sudo apt install curl -y
curl -sSL https://packages.sury.org/php/README.txt | sudo bash -x
sudo apt update
sudo apt install php8.2-{cli,curl,bcmath,dom,fpm,gd,mbstring,mysql,opcache,redis,sqlite3,swoole,zip}
```

### WAMP 环境

推荐使用 [Laragon](https://laragon.org/download/) 集成开发环境。

## 安装后端工程依赖

```
git clone https://gitee.com/phpmall/phpmall.git

cd phpmall
composer config -g repos.packagist composer https://packagist.pages.dev
composer install -oW
cp .env.example .env
php artisan key:generate
```

## 创建数据库

```
CREATE DATABASE `phpmall` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci';
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

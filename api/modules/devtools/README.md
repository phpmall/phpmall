# Juling-DevTools

Juling DevTools 是一款开发辅助工具，帮助开发者实现功能的 CRUD 操作，快速实现业务落地。

## 安装

安装 laravel-devtools 工具 的 composer 包

```
composer require juling/laravel-devtools --dev
```

## 使用

工具初始化

```
php artisan gen:init
```

生成数据表实体类

```
php artisan gen:entity
```

生成数据表模型类

```
php artisan gen:model
```

生成数据表DAO类

```
php artisan gen:dao
```

生成数据表服务类

```
php artisan gen:service
```

## 其他

生成 swagger 接口文档

```
php artisan gen:swagger
```

生成请求和响应类接口（typescript interface）

```
php artisan gen:interface
```

## License

Apache-2.0
# PHPMall 项目代码优化待办事项

## 概述

本文档整理了 PHPMall 项目代码需要优化的各个方面，包括模型层、控制器层、服务层、数据库、路由等。同时包含 PHP 8.2+ 升级的具体任务。

---

## 一、高优先级

### 1.1 模型层优化 (Models)

**问题**: Model 文件中缺少 Eloquent 关联、类型转换、属性隐藏

**优化内容**:

```php
// 1. 添加关联关系 (app/Models/*.php)
public function orders(): HasMany
{
    return $this->hasMany(OrderInfo::class, 'user_id');
}

// 2. 添加类型转换
protected function casts(): array
{
    return [
        'birthday' => 'date',
        'is_validated' => 'boolean',
        'is_on_sale' => 'boolean',
    ];
}

// 3. 隐藏敏感字段
protected $hidden = ['password', 'salt', 'ec_salt'];
```

### 1.2 控制器层优化

- **Form Request**: 创建 `app/Requests/` 替代控制器内验证
- **移除超全局变量**: `$_GET`, `$_POST`, `$_REQUEST` 改用 `$request->input()`
- **业务逻辑外置**: 将业务逻辑提取到 Service 层
- **统一响应格式**: 使用 API Resource

### 1.3 安全性修复

- 密码 MD5 改用 bcrypt (`Hash::make()`)
- 移除 `env()` 直接调用，改用 `config()`
- 敏感字段添加到 `$hidden`

### 1.4 PHP 8.2+ 兼容性

| 问题 | 修复方式 |
|------|----------|
| 隐式 Nullable 参数 | 添加 `?` 或提供默认值 |
| `${var}` 字符串插值 | 改为 `{$var}` |
| 未定义数组键访问 | 使用 `isset()` 或 `??` |
| 弱类型比较 `0 == "str"` | 改用严格比较 `===` |
| FCKEditor 过时 | 替换为 CKEditor/TinyMCE |

---

## 二、中优先级

### 2.1 服务层优化

- 统一 Service 层结构（BundleService → 标准 Service）
- 实现 Repository 模式，集中数据查询逻辑

### 2.2 Helper 类优化

- 拆分 `CommonHelper.php`、`OrderHelper.php` 等大文件
- `serialize()`/`unserialize()` 改用 JSON
- `DB::table()` 改用 Eloquent 模型

### 2.3 数据库迁移优化

- 迁移文件时间戳使用唯一值
- 添加缺失的数据库索引
- 添加外键约束

### 2.4 视图层优化

- 旧模板引擎 `$this->display()` 迁移到 Blade
- 视图文件从 `app/Modules/*/Views/` 迁移到 `resources/views/`

### 2.5 测试补充

- 添加业务功能测试
- 逐步提高测试覆盖率

---

## 三、低优先级

### 3.1 常量优化

```php
// 替换 define()
enum OrderStatus: int
{
    case Pending = 0;
    case Paid = 1;
    case Shipped = 2;
}
```

### 3.2 代码风格

- 运行 `vendor/bin/pint` 统一代码风格
- 添加返回类型声明

### 3.3 配置优化

- 时区改为 `Asia/Shanghai`
- 硬编码配置迁移到 `.env`

---

## 建议执行顺序

1. **安全性修复** - 密码哈希、敏感信息
2. **PHP 8.2+ 兼容性** - 修复废弃警告
3. **模型层优化** - 关联关系、Casts、Hidden
4. **控制器层优化** - Form Request、移除超全局变量
5. **服务层和 Helper 整合**
6. **视图层迁移**
7. **测试补充**

---

## 快速命令

```bash
# 静态分析
phpstan analyse

# 代码格式化
vendor/bin/pint

# 运行测试
php artisan test

# 生成 API 文档
php artisan l5-swagger:generate
```

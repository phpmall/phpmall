# 架构

- 应用模块：Auth User Shop
- 应用服务：Goods Address Order
- 运行时：Swoole
- 中间件：Nginx MySQL Redis
- 系统服务：Debian
- 基础设施：ECS

### 项目目录介绍

```
app                   核心应用文件
  Http
    Controllers       控制器文件
      Auth            认证接口
      Console         平台接口
      Mobile          移动端接口
      Seller          商家接口
      User            消费者接口
      Web             网页
    Middleware        中间件
    Requests          请求类
    Response          响应类
  Exceptions          异常文件
  Models              数据库表模型
  Providers           服务提供者
  Services            核心业务服务
  Support             支持文件
bootstrap             核心框架启动文件
```

开发实行分层调用：

```
API 网关 -> index.php -> 启动核心框架
	-> request 请求验证层（表单验证）
	-> controller 按照MCA路由分发处理请求（M：模块，C：控制器，A：处理方法）
	-> service 调用业务逻辑服务层
	-> manager 通用逻辑层（如外部短信服务等）
	-> model 调用数据表关系模型层
	-> DB 底层查询数据库
```

返回的数据按照逆向数据流响应给客户端的API.

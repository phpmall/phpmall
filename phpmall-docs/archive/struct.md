# 项目结构

```
app                   核心应用文件
  controller          控制器文件
	console           平台接口
	shop              店铺接口
	user              消费者接口
	wechat            微信接口
  exception           异常文件
  handler             微信公众平台消息处理类
  middleware          中间件
  model               数据库表模型
  provider            服务提供者
  request             请求类
  response            响应类
  service             核心业务服务
  support             支持文件
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

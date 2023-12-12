
## 目录结构

```
app                   核心应用文件
  controller          控制器文件
	console           平台接口
	user              消费者接口
	web               网页
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
config                全局配置
database              数据库文件
public                公网访问目录
resources             资源文件
routes                路由配置
storage               存储目录
tests                 测试目录
vendor                composer依赖
```

## 请求周期

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

## 配置伪静态

打开生成的 nginx 配置文件，替换 thinkphp 官方推荐的伪静态规则：

https://www.kancloud.cn/manual/thinkphp6_0/1037488

```
location / {
   if (!-e $request_filename) {
   		rewrite ^(.*)$ /index.php?s=/$1 last;
    }
}
```

## 调试模式

应用默认是部署模式，在开发阶段，可以修改环境变量APP_DEBUG开启调试模式，上线部署后切换到部署模式。

本地开发的时候可以在应用根目录下面定义.env文件。

## 开发环境

- 安装 laragon 集成环境 https://laragon.org/download/
- 下载 PHP8 https://windows.php.net/download/#php-8.1-ts-vs16-x64 ，并解压到 laragon 的 bin/php 目录下
- 启动 laragon，选择 PHP 版本
- 安装 sourcetree https://www.sourcetreeapp.com/

## 在线文档

[浏览文档](https://docs.phpmall.net)

# 部署

### docker 打包

```
docker build -t focite/phpmall:www-v1.0 .
```

### nginx代理

当需要直接提供外网访问时，建议在商城前增加一个nginx代理，这样有以下好处。

- 静态资源由nginx处理，让商城专注业务逻辑处理
- 让多个服务共用80、443端口，通过域名区分不同站点，实现单台服务器部署多个站点
- 能够实现php-fpm与cli架构共存
- nginx代理ssl实现https，更加简单高效
- 能够严格过滤外网一些不合法请求

nginx代理示例

```
upstream phpmall {
    server 127.0.0.1:8080;
    keepalive 10240;
}

server {
  server_name 站点域名;
  listen 80;
  access_log off;
  root /your/phpmall/public;

  location / {
      proxy_http_version 1.1;
      proxy_set_header Host $host;
      proxy_set_header X-Real-IP $remote_addr;
      proxy_set_header Connection "";
      if (!-f $request_filename){
          proxy_pass http://phpmall;
      }
  }
}
```

### 前端资源发布

- 安装 nvm

```shell
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
```

- 安装 nodejs

```shell
nvm install --lts
```

- 升级 npm

```shell
npm i -g npm
```

- 安装 pnpm

```shell
npm i -g pnpm
```

- 安装 bower

```shell
npm i -g bower
```

- 安装前端依赖
```shell
bower install
cd resource/js/console/
pnpm i
pnpm build
cd ../seller/
pnpm i
pnpm build
cd ../supplier/
pnpm i
pnpm build
cd ../user/
pnpm i
pnpm build
```

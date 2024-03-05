# Deploy

### 生成部署密钥

```
ssh-keygen -t ed25519 -C "deploy@focite.com"
```

### 获取公钥并设置到git仓库的部署公钥

```
cat ~/.ssh/id_ed25519.pub
```

https://gitee.com/phpmall/phpmall/deploy_keys

### 拉取代码到服务器

```
mkdir /home/wwwroot
cd /home/wwwroot
git clone git@gitee.com:phpmall/phpmall.git
```

### 构建Web镜像

```
cd /home/wwwroot/phpmall/web
docker build phpmall:1.0.0 .
```

### 运行docker镜像

```
docker run -d -p 3000:3000 phpmall:1.0.0
```

### 运行Nginx网关

```
docker run -d --name nginx -v /home/wwwroot/phpmall/docker/nginx/:/etc/nginx/conf.d/ -p 80:80 -p 443:443 nginx
```

### 查看docker容器网关

如果容器内需要访问宿主机的端口，可以通过docker容器网关IP来访问。

```
docker inspect nginx
```

在返回的结果中查看网关IP

```
...
 "Networks": {
	"bridge": {
		"IPAMConfig": null,
		"Links": null,
		"Aliases": null,
		"NetworkID": "598521f218aa92731f0583ba0d37bbc2f88f075e90397be60871424b35c3201c",
		"EndpointID": "42731069a964b6895d05ec63ca8ad7fc184897af5598c92f4eaeabbc7d26527b",
		"Gateway": "172.17.0.1", // 网关IP
		"IPAddress": "172.17.0.2",
		"IPPrefixLen": 16,
		"IPv6Gateway": "",
		"GlobalIPv6Address": "",
		"GlobalIPv6PrefixLen": 0,
		"MacAddress": "02:42:ac:11:00:02",
		"DriverOpts": null
	}
}
```
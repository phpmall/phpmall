docker rmi ecboot:latest
docker build -t ecboot:latest docs/docker/prod
docker tag ecboot:latest registry.cn-shanghai.aliyuncs.com/juling/ecboot:latest
docker push registry.cn-shanghai.aliyuncs.com/juling/ecboot:latest

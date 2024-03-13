docker rmi example:latest
docker build -t example:latest docker/prod
docker tag example:latest registry.cn-shanghai.aliyuncs.com/juling/example:latest
docker push registry.cn-shanghai.aliyuncs.com/juling/example:latest

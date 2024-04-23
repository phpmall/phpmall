docker rmi phpmall:latest
docker build -t phpmall:latest docs/docker/prod
docker tag phpmall:latest registry.cn-shanghai.aliyuncs.com/juling/phpmall:latest
docker push registry.cn-shanghai.aliyuncs.com/juling/phpmall:latest

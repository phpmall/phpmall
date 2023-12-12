docker rmi phpmall:latest
docker build -t phpmall:latest .
docker tag phpmall:latest registry.cn-shanghai.aliyuncs.com/focite/phpmall:latest
docker push registry.cn-shanghai.aliyuncs.com/focite/phpmall:latest

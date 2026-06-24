#!/bin/bash

CURRENT_DIR=/root/sites/demo.phpmall.net

cd $CURRENT_DIR
echo "Current directory is $CURRENT_DIR"

# 拉取代码
git pull

# 构建容器
docker build -t phpmall:latest .
sleep 1s

# 启动容器
docker stop phpmall
docker rm phpmall
docker run -d --restart always --name phpmall -p 8001:8000 --network phpmall-network phpmall:latest

# 清理镜像
docker rmi -f  `docker images | grep '<none>' | awk '{print $3}'`

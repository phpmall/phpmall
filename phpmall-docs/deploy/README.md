# 部署

### 安装 Supervisor
```
# debian
apt install supervisor
```

### 配置 Supervisor
```
cp demo.phpmall.net_supervisor.conf /etc/supervisor/conf.d/
```

### 启动 Supervisor
运行下面的命令基于配置文件启动 Supervisor 程序：
```
supervisord -c /etc/supervisor/supervisord.conf
```

### 使用 supervisorctl 管理项目
```
# 启动 phpmall 应用
supervisorctl start phpmall
# 重启 phpmall 应用
supervisorctl restart phpmall
# 停止 phpmall 应用
supervisorctl stop phpmall  
# 查看所有被管理项目运行状态
supervisorctl status
# 重新加载配置文件
supervisorctl update
# 重新启动所有程序
supervisorctl reload
```

### 配置 crontab
```
select-editor
crontab -e
# 添加计划任务
* */1 * * * bash /home/wwwroot/demo.phpmall.net/deploy.sh
```

### 清除注释

```regexp
// 清除代码注释
^\s+?\/\/.+\n

// 清除swagger注解
^.+\#\[OA\\.+\n
```

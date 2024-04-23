cd /home/wwwroot/demo.phpmall.net
git pull
cd server
composer u --no-dev -o
php artisan optimize
supervisorctl restart phpmall

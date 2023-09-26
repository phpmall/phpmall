cd /home/wwwroot/phpmall/

git pull

cur_dir=$(pwd)

BackendBuild()
{
    cd $cur_dir
    composer u --no-dev -o
    php artisan optimize
    php artisan migrate --force
    php artisan db:seed --force
    supervisorctl reload
}

FrontendBuild()
{
    cd $cur_dir/desktop
    pnpm install
    pnpm run build-only
    ossutil64 cp -rf dist oss://phpmall-demo/ # --endpoint=oss-cn-hongkong.aliyuncs.com
}

MobileBuild()
{
    cd $cur_dir/mobile
    pnpm install
    pnpm run build:h5
    ossutil64 cp -rf dist/build/h5 oss://phpmall-demo/mobile # --endpoint=oss-cn-hongkong.aliyuncs.com
}

BackendBuild
FrontendBuild
MobileBuild

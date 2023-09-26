cd /home/wwwroot/phpmall/

cur_dir=$(pwd)

FrontendBuild()
{
    cd $cur_dir
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

BackendBuild()
{
    cd $cur_dir
    composer u
    php artisan optimize
    php artisan migrate --force
    php artisan db:seed --force
    supervisorctl reload
}

git pull

BackendBuild
FrontendBuild
MobileBuild

cd /home/wwwroot/phpmall/

git pull

cur_dir=$(pwd)

Stack=$1
if [ "${Stack}" = "" ]; then
    Stack="all"
else
    Stack=$1
fi

BackendBuild()
{
    cd $cur_dir/phpmall-server
    composer u --no-dev -oW
    php artisan optimize
    php artisan migrate --force
    php artisan db:seed --force
    # supervisorctl reload
}

FrontendBuild()
{
    cd $cur_dir/phpmall-web
    pnpm install
    pnpm run build
    ossutil cp -rf dist oss://phpmall-demo/ --endpoint=oss-cn-shanghai.aliyuncs.com
}

MobileBuild()
{
    cd $cur_dir/phpmall-mobile
    pnpm install
    pnpm run build:h5
    ossutil cp -rf dist/build/h5 oss://phpmall-demo/mobile --endpoint=oss-cn-shanghai.aliyuncs.com
}

DocsBuild()
{
    cd $cur_dir/phpmall-docs
    ossutil cp -rf api oss://phpmall-demo/api --endpoint=oss-cn-shanghai.aliyuncs.com
}

if [[ "${Stack}" = "all" ]]; then
  BackendBuild
  FrontendBuild
  MobileBuild
  DocsBuild
elif [[ "${Stack}" = "backend" ]]; then
  BackendBuild
elif [[ "${Stack}" = "frontend" ]]; then
  FrontendBuild
elif [[ "${Stack}" = "mobile" ]]; then
  MobileBuild
elif [[ "${Stack}" = "docs" ]]; then
  DocsBuild
fi

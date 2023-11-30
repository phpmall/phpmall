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
    cd $cur_dir
    composer u --no-dev -o
    php artisan optimize
    php artisan migrate --force
    php artisan db:seed --force
    # supervisorctl reload
}

FrontendBuild()
{
    cd $cur_dir/frontend
    pnpm install
    pnpm run build
    ossutil cp -rf dist oss://shprint/ --endpoint=oss-cn-hongkong.aliyuncs.com
}

MobileBuild()
{
    cd $cur_dir/mobile
    pnpm install
    pnpm run build:h5
    ossutil cp -rf dist/build/h5 oss://shprint/mobile --endpoint=oss-cn-hongkong.aliyuncs.com
}

DocsBuild()
{
    cd $cur_dir
    ossutil cp -rf docs/api oss://shprint/api --endpoint=oss-cn-hongkong.aliyuncs.com
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

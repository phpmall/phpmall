cd /home/wwwroot/www.phpmall.net

git pull

cur_dir=$(pwd)

Stack=$1
if [ "${Stack}" = "" ]; then
    Stack="all"
else
    Stack=$1
fi

npm i -g npm
npm i -g pnpm
npm i -g bun

BackendBuild()
{
    cd $cur_dir
    composer u --no-dev -oW
    php artisan optimize
    php artisan migrate:fresh --force
    php artisan db:seed --force
    supervisorctl restart phpmall
}

FrontendBuild()
{
    cd $cur_dir/frontend
    pnpm install
    pnpm run build-only
    # ossutil rm -rf oss://phpmall-demo/assets # --endpoint=oss-cn-hongkong.aliyuncs.com
    # ossutil cp -rf dist/ oss://phpmall-demo/
}

MobileBuild()
{
    cd $cur_dir/mobile
    pnpm install
    pnpm run build:h5
    # ossutil rm -rf oss://phpmall-demo/mobile
    # ossutil cp -rf dist/build/h5 oss://phpmall-demo/mobile
}

DocsBuild()
{
    cd $cur_dir
    pnpm run docs:build
    cp -a docs/api docs/.vitepress/dist/
    # ossutil rm -rf oss://phpmall-demo/docs
    # ossutil cp -rf docs/ oss://phpmall-demo/docs
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

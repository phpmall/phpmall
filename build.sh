cd /home/wwwroot/demo.phpmall.net

git pull

cur_dir=$(pwd)

Stack=$1
if [ "${Stack}" = "" ]; then
    Stack="all"
else
    Stack=$1
fi

bun upgrade

BackendBuild()
{
    cd $cur_dir/phpmall-api
    composer u --no-dev -oW
    php artisan optimize
    php artisan migrate:fresh --force
    php artisan db:seed --force
    supervisorctl reload
}

MobileBuild()
{
    cd $cur_dir/phpmall-mobile
    bun install
    bun run build:h5 --base=/mobile/
    ossutil rm -rf oss://phpmall-demo/mobile
    ossutil cp -rf dist/build/h5 oss://phpmall-demo/mobile
}

FrontendBuild()
{
    cd $cur_dir/phpmall-web
    bun install
    bun run build-only
    ossutil rm -rf oss://phpmall-demo/assets
    ossutil cp -rf dist/ oss://phpmall-demo/
}

DocsBuild()
{
    cd $cur_dir
    ossutil rm -rf oss://phpmall-demo/docs
    ossutil cp -rf docs/ oss://phpmall-demo/docs
}

if [[ "${Stack}" = "all" ]]; then
  BackendBuild
  MobileBuild
  FrontendBuild
  DocsBuild
elif [[ "${Stack}" = "api" ]]; then
  BackendBuild
elif [[ "${Stack}" = "mobile" ]]; then
  MobileBuild
elif [[ "${Stack}" = "web" ]]; then
  FrontendBuild
elif [[ "${Stack}" = "docs" ]]; then
  DocsBuild
fi

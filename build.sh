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
    cd $cur_dir
    composer u --no-dev -oW
    php artisan optimize
    php artisan migrate:fresh --force
    php artisan db:seed --force
    supervisorctl reload
}

FrontendBuild()
{
    cd $cur_dir/frontend
    bun install
    bun run build-only
    ossutil rm -rf oss://phpmall-demo/assets
    ossutil cp -rf dist/ oss://phpmall-demo/
}

MobileBuild()
{
    cd $cur_dir/mobile
    bun install
    bun run build:h5 --base=/mobile/
    ossutil rm -rf oss://phpmall-demo/mobile
    ossutil cp -rf dist/build/h5 oss://phpmall-demo/mobile
}

DocsBuild()
{
    cd $cur_dir
    ossutil rm -rf oss://phpmall-demo/docs
    ossutil cp -rf docs/ oss://phpmall-demo/docs
}

if [[ "${Stack}" = "all" ]]; then
  BackendBuild
  FrontendBuild
  MobileBuild
  DocsBuild
elif [[ "${Stack}" = "api" ]]; then
  BackendBuild
elif [[ "${Stack}" = "frontend" ]]; then
  FrontendBuild
elif [[ "${Stack}" = "mobile" ]]; then
  MobileBuild
elif [[ "${Stack}" = "docs" ]]; then
  DocsBuild
fi

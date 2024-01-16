cd /home/wwwroot/demo.phpmall.net

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
    composer u --no-dev -oW
    php artisan optimize
    php artisan migrate:fresh --force
    php artisan db:seed --force
    supervisorctl reload
}

AdminBuild()
{
    cd $cur_dir/resources/admin
    bun install
    bun run build-only
    rm -rf $cur_dir/public/admin
    mv dist $cur_dir/public/admin
}

SellerBuild()
{
    cd $cur_dir/resources/seller
    bun install
    bun run build-only
    rm -rf $cur_dir/public/seller
    mv dist $cur_dir/public/seller
}

PortalBuild()
{
    cd $cur_dir
    bun install
    bun run build
}

MobileBuild()
{
    cd $cur_dir/resources/mobile
    bun install
    bun run build:h5
    rm -rf $cur_dir/public/mobile
    mv dist/build/h5 $cur_dir/public/mobile
}

DocsBuild()
{
    cd $cur_dir/docs
}

if [[ "${Stack}" = "all" ]]; then
  BackendBuild
  AdminBuild
  SellerBuild
  PortalBuild
  MobileBuild
  DocsBuild
elif [[ "${Stack}" = "backend" ]]; then
  BackendBuild
elif [[ "${Stack}" = "admin" ]]; then
  AdminBuild
elif [[ "${Stack}" = "seller" ]]; then
  SellerBuild
elif [[ "${Stack}" = "portal" ]]; then
  PortalBuild
elif [[ "${Stack}" = "mobile" ]]; then
  MobileBuild
elif [[ "${Stack}" = "docs" ]]; then
  DocsBuild
fi

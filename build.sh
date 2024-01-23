cd /home/wwwroot/demo.phpmall.net

bun upgrade

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
    php artisan migrate:fresh --force
    php artisan db:seed --force
    supervisorctl reload
}

AdminBuild()
{
    cd $cur_dir/phpmall-admin
    bun install
    bun run build-only
    rm -rf $cur_dir/phpmall-server/public/admin
    mv dist $cur_dir/phpmall-server/public/admin
}

SellerBuild()
{
    cd $cur_dir/phpmall-seller
    bun install
    bun run build-only
    rm -rf $cur_dir/phpmall-server/public/seller
    mv dist $cur_dir/phpmall-server/public/seller
}

SupplierBuild()
{
    cd $cur_dir/phpmall-supplier
    bun install
    bun run build-only
    rm -rf $cur_dir/phpmall-server/public/supplier
    mv dist $cur_dir/phpmall-server/public/supplier
}

MobileBuild()
{
    cd $cur_dir/phpmall-mobile
    bun install
    bun run build:h5
    rm -rf $cur_dir/phpmall-server/public/mobile
    mv dist/build/h5 $cur_dir/phpmall-server/public/mobile
}

PortalBuild()
{
    cd $cur_dir/phpmall-web
    bun install
    bun run build-only
    rm -rf $cur_dir/phpmall-server/public/index.html
    rm -rf $cur_dir/phpmall-server/public/assets
    mv dist/{index.html,assets} $cur_dir/phpmall-server/public/
}

DocsBuild()
{
    rm -rf $cur_dir/phpmall-server/public/docs
    cp -a docs $cur_dir/phpmall-server/public/docs
}

if [[ "${Stack}" = "all" ]]; then
  BackendBuild
  AdminBuild
  SellerBuild
  SupplierBuild
  MobileBuild
  PortalBuild
  DocsBuild
elif [[ "${Stack}" = "backend" ]]; then
  BackendBuild
elif [[ "${Stack}" = "admin" ]]; then
  AdminBuild
elif [[ "${Stack}" = "seller" ]]; then
  SellerBuild
elif [[ "${Stack}" = "supplier" ]]; then
  SupplierBuild
elif [[ "${Stack}" = "portal" ]]; then
  PortalBuild
elif [[ "${Stack}" = "mobile" ]]; then
  MobileBuild
elif [[ "${Stack}" = "docs" ]]; then
  DocsBuild
fi

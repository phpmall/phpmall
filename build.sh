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
    cd $cur_dir/phpmall-server
    composer u --no-dev -oW
    php artisan optimize
    php artisan migrate:fresh --force
    php artisan db:seed --force
    supervisorctl reload
}

FrontendBuild()
{
    local module="$1"
    cd $cur_dir/phpmall-${module}

    bun install

    local to="$2"
    if [ ${module} = "mobile" ]; then
        bun run build:h5 --base=/${to}/
        ossutil cp -rf dist/build/h5 oss://phpmall-demo/${to}
    else
        if [ ${module} = "web" ]; then
            bun run build-only
            ossutil cp -rf dist oss://phpmall-demo/
            # rm -rf $cur_dir/phpmall-server/public/assets/
            # rm -rf $cur_dir/phpmall-server/public/favicon.icon
            # rm -rf $cur_dir/phpmall-server/public/index.html
            # cp -a dist/* $cur_dir/phpmall-server/public/
        else
            bun run build-only --base=/${to}/
            ossutil cp -rf dist oss://phpmall-demo/${to}
            # rm -rf $cur_dir/phpmall-server/public/${to}
            # cp -a dist $cur_dir/phpmall-server/public/${to}
        fi
    fi
}

DocsBuild()
{
    cd $cur_dir
    ossutil cp -rf docs/ oss://phpmall-demo/docs
    # rm -rf $cur_dir/phpmall-server/public/docs
    # cp -a docs $cur_dir/phpmall-server/public/docs
}

if [[ "${Stack}" = "all" ]]; then
  BackendBuild
  FrontendBuild admin
  FrontendBuild mobile
  FrontendBuild seller
  FrontendBuild supplier
  FrontendBuild user
  FrontendBuild web
  DocsBuild
elif [[ "${Stack}" = "backend" ]]; then
  BackendBuild
elif [[ "${Stack}" = "admin" ]]; then
  FrontendBuild admin
elif [[ "${Stack}" = "mobile" ]]; then
  FrontendBuild mobile
elif [[ "${Stack}" = "seller" ]]; then
  FrontendBuild seller
elif [[ "${Stack}" = "supplier" ]]; then
  FrontendBuild supplier
elif [[ "${Stack}" = "user" ]]; then
  FrontendBuild user
elif [[ "${Stack}" = "web" ]]; then
  FrontendBuild web
elif [[ "${Stack}" = "docs" ]]; then
  DocsBuild
fi

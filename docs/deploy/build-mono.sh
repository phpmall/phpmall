cur_dir=$(pwd)

FrontendBuild()
{
    local module="$1"
    cd $cur_dir/phpmall-${module}

    pnpm install

    local to="$2"
    if [ ${module} = "mobile" ]; then
        pnpm run build:h5 --base=/${to}/
        rm -rf $cur_dir/phpmall-server/public/${to}
        cp -a dist/build/h5 $cur_dir/phpmall-server/public/${to}
    else
        if [ ${module} = "web" ]; then
            pnpm run build-only
            rm -rf $cur_dir/phpmall-server/public/assets/
            rm -rf $cur_dir/phpmall-server/public/favicon.icon
            rm -rf $cur_dir/phpmall-server/public/index.html
            cp -a dist/* $cur_dir/phpmall-server/public/
        else
            pnpm run build-only --base=/${to}/
            rm -rf $cur_dir/phpmall-server/public/${to}
            cp -a dist $cur_dir/phpmall-server/public/${to}
        fi
    fi
}

FrontendBuild auth passport
FrontendBuild manager admin
FrontendBuild mobile mobile
FrontendBuild seller seller
FrontendBuild supplier supplier
FrontendBuild user home
FrontendBuild web

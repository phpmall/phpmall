cur_dir=$(pwd)

FrontendBuild()
{
    local module="$1"
    cd $cur_dir/phpmall-${module}

    pnpm install

    local to="$2"
    if [ ${module} = "mobile" ]; then
        pnpm run build:h5 --base=/${to}/
        ossutil64 cp -rf dist/build/h5/ oss://phpmall-demo/${to}
    else
        if [ ${module} = "web" ]; then
            pnpm run build-only
            ossutil64 cp -rf dist/ oss://phpmall-demo/
        else
            pnpm run build-only --base=/${to}/
            ossutil64 cp -rf dist/ oss://phpmall-demo/${to}
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

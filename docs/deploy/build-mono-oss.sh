cur_dir=$(pwd)

FrontendBuild()
{
    local module="$1"
    cd $cur_dir/${module}

    pnpm install

    local to="$2"
    if [ ${module} = "mobile" ]; then
        pnpm run build:h5 --base=/${to}/
        ossutil64 cp -rf dist/build/h5/ oss://phpmall-demo/${to}
    else
        pnpm run build-only
        ossutil64 cp -rf dist/ oss://phpmall-demo/
    fi
}

FrontendBuild client mobile
FrontendBuild web

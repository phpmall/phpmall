cur_dir=$(pwd)

FrontendBuild()
{
    local module="$1"
    cd $cur_dir/phpmall-${module}

    pnpm install

    if [ ${module} = "mobile" ]; then
        pnpm run build:h5
    else
        if [ ${module} = "web" ]; then
            pnpm run build-only
        else
            pnpm run build-only
        fi
    fi

    local oss="$2"
    if [ ${module} = "mobile" ]; then
        ossutil64 cp -rf dist/build/h5/ oss://${oss}/
    else
        ossutil64 cp -rf dist/ oss://${oss}/
    fi
}

FrontendBuild admin phpmall-console
FrontendBuild mobile phpmall-mobile
FrontendBuild passport phpmall-passport
FrontendBuild seller phpmall-seller
FrontendBuild supplier phpmall-supplier
FrontendBuild user phpmall-home
FrontendBuild web phpmall-official

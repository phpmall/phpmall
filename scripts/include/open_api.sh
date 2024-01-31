Get_Modules() {
    local directory="app/Api"

    # 查找目录下的所有目录
    local modules=($(ls "$directory"))

    for item in "${modules[@]}"
    do
        local result=()
        local c="app/Api/${item}/Controllers/"
        if [ -d "$c" ]; then
            result+=($c)
            c=("app/Api/${item}/Requests/")
            if [ -d "$c" ]; then
                result+=($c)
            fi
            c=("app/Api/${item}/Responses/")
            if [ -d "$c" ]; then
                result+=($c)
            fi
        fi

        result+=($(Get_Bundles ${item}))

        vendor/bin/openapi ${result[@]} -o storage/app/ts/${item,,}.json -f json
    done
}

Get_Bundles()
{
    local directory="app/Bundles"
    local module=$1

    # 查找目录下的所有目录
    local bundles=($(ls "$directory"))

    local result=()
    for item in "${bundles[@]}"
    do
        local c="app/Bundles/${item}/Controllers/${module}/"
        if [ -d "$c" ]; then
            result+=($c)
            c="app/Bundles/${item}/Requests/"
            if [ -d "$c" ]; then
                result+=($c)
            fi
            c="app/Bundles/${item}/Responses/"
            if [ -d "$c" ]; then
                result+=($c)
            fi
        fi
    done

    echo ${result[@]}
}

Echo_Green '------------------------------'
Echo_Green ' 生成API路由配置'
Echo_Green '------------------------------'

php artisan gen:route

Echo_Green '------------------------------'
Echo_Green ' 生成swagger接口文档'
Echo_Green '------------------------------'

Get_Modules
rm -rf docs/api/*.json
cp storage/app/ts/*.json docs/api/

Echo_Green '------------------------------'
Echo_Green ' 生成typescript接口'
Echo_Green '------------------------------'

php artisan gen:typescript

rm -rf mobile/src/{services,types}
mkdir -p mobile/src/{services,types}
cp storage/app/ts/services/{auth,portal,user}.ts mobile/src/services/
cp storage/app/ts/types/{auth,portal,user}.d.ts mobile/src/types/

rm -rf frontend/src/{services,types}
cp -a storage/app/ts/{services,types} frontend/src/


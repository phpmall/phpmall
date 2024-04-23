Color_Text()
{
  echo -e " \e[0;$2m$1\e[0m"
}

Echo_Red()
{
  echo $(Color_Text "$1" "31")
}

Echo_Green()
{
  echo $(Color_Text "$1" "32")
}

Gen_OpenAPI() {
    local directory="app/API"

    # 查找目录下的所有目录
    local modules=($(ls "$directory"))

    for item in "${modules[@]}"
    do
        local result=()
        local c="app/API/${item}/Controllers/"
        if [ -d "$c" ]; then
            result+=($c)
            c=("app/API/${item}/Requests/")
            if [ -d "$c" ]; then
                result+=($c)
            fi
            c=("app/API/${item}/Responses/")
            if [ -d "$c" ]; then
                result+=($c)
            fi
        fi

        c=("app/Http/Responses/")
        if [ -d "$c" ]; then
            result+=($c)
        fi

        result+=($(Get_Bundles ${item}))

        vendor/bin/openapi ${result[@]} -o storage/app/openapi/${item,,}.json -f json
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
        local c="app/Bundles/${item}/API/${module}/"
        if [ -d "$c" ]; then
            result+=($c)
        fi
        # c="app/Bundles/${item}/Requests/${module}/"
        c="app/Bundles/${item}/Requests/"
        if [ -d "$c" ]; then
            result+=($c)
        fi
        # c="app/Bundles/${item}/Responses/${module}/"
        c="app/Bundles/${item}/Responses/"
        if [ -d "$c" ]; then
            result+=($c)
        fi
    done

    echo ${result[@]}
}

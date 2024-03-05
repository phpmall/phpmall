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
    local directory="app/Api"

    # 查找目录下的所有目录
    local modules=($(ls "$directory"))

    for item in "${modules[@]}"
    do
        local result=()        
        local c="app/Api/${item}/"
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
        result+=("app/Http/Responses/")

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
        fi
        c="app/Bundles/${item}/Requests/${module}/"
        if [ -d "$c" ]; then
            result+=($c)
        fi
        c="app/Bundles/${item}/Responses/${module}/"
        if [ -d "$c" ]; then
            result+=($c)
        fi
    done

    echo ${result[@]}
}

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
            result+=("app/Bundles/${item}/Requests/")
            result+=("app/Bundles/${item}/Responses/")
        fi
    done

    echo ${result[@]}
}

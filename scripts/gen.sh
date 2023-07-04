#!/usr/bin/env bash
export PATH=$PATH:/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin

cur_dir=$(pwd)
cd $cur_dir

Stack=$1
if [ "${Stack}" = "" ]; then
    Stack="all"
else
    Stack=$1
fi


. scripts/include/support.sh


if [[ "${Stack}" = "all" ]]; then
  . scripts/include/code_gen.sh
  . scripts/include/code_pint.sh
  . scripts/include/open_api.sh
elif [[ "${Stack}" = "gen" ]]; then
  . scripts/include/code_gen.sh
elif [[ "${Stack}" = "pint" ]]; then
  . scripts/include/code_pint.sh
elif [[ "${Stack}" = "api" ]]; then
  . scripts/include/open_api.sh
fi

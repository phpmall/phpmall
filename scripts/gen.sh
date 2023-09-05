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
elif [[ "${Stack}" = "code" ]]; then
  . scripts/include/code_gen.sh
elif [[ "${Stack}" = "pint" ]]; then
  . scripts/include/code_pint.sh
elif [[ "${Stack}" = "api" ]]; then
  . scripts/include/open_api.sh
elif [[ "${Stack}" = "analyse" ]]; then
  Echo_Green '------------------------------'
  Echo_Green ' 执行代码静态分析'
  Echo_Green '------------------------------'
  vendor/bin/phpstan analyse app tests
fi

Echo_Green '------------------------------'
Echo_Green ' Git恢复部分代码'
Echo_Green '------------------------------'

# git checkout app/Repositories/UserRepository.php

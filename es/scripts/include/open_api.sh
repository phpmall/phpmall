Echo_Green '------------------------------'
Echo_Green ' 生成API路由配置'
Echo_Green '------------------------------'

php artisan gen:route

Echo_Green '------------------------------'
Echo_Green ' 生成swagger接口文档'
Echo_Green '------------------------------'

Gen_OpenAPI

rm -rf ../ecboot-docs/api/*.json
cp storage/app/openapi/*.json ../ecboot-docs/api/

Echo_Green '------------------------------'
Echo_Green ' 生成typescript接口'
Echo_Green '------------------------------'

php artisan gen:typescript

rm -rf ../ecboot-mobile/src/{services,types}
mkdir -p ../ecboot-mobile/src/{services,types}
cp storage/app/ts/services/member.ts ../ecboot-mobile/src/services/
cp storage/app/ts/types/member.d.ts ../ecboot-mobile/src/types/

rm -rf ../ecboot-admin/src/{services,types}
cp -a storage/app/ts/{services,types} ../ecboot-admin/src/

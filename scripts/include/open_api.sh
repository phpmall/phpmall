Echo_Green '------------------------------'
Echo_Green ' 生成API路由配置'
Echo_Green '------------------------------'

php artisan gen:route

Echo_Green '------------------------------'
Echo_Green ' 生成swagger接口文档'
Echo_Green '------------------------------'

Gen_OpenAPI

rm -rf docs/api/*.json
cp storage/app/openapi/*.json docs/api/

Echo_Green '------------------------------'
Echo_Green ' 生成typescript接口'
Echo_Green '------------------------------'

php artisan gen:typescript

rm -rf resources/mobile/src/{services,types}
mkdir -p resources/mobile/src/{services,types}
cp storage/app/ts/services/{auth,portal,user}.ts resources/mobile/src/services/
cp storage/app/ts/types/{auth,portal,user}.d.ts resources/mobile/src/types/

rm -rf resources/web/src/{services,types}
cp -a storage/app/ts/{services,types} resources/web/src/

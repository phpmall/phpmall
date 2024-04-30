Echo_Green '------------------------------'
Echo_Green ' 生成API路由配置'
Echo_Green '------------------------------'

php artisan gen:route

Echo_Green '------------------------------'
Echo_Green ' 生成swagger接口文档'
Echo_Green '------------------------------'

rm -rf storage/app/openapi/*.json
Gen_OpenAPI

rm -rf docs/api/*.json
cp storage/app/openapi/*.json docs/api/

Echo_Green '------------------------------'
Echo_Green ' 生成typescript接口'
Echo_Green '------------------------------'

rm -rf storage/app/ts/*/*.ts
php artisan gen:typescript

rm -rf mobile/src/{services,types}
mkdir -p mobile/src/{services,types}
cp storage/app/ts/services/*.ts mobile/src/services/
cp storage/app/ts/types/*.d.ts mobile/src/types/

rm -rf frontend/src/{services,types}
mkdir -p frontend/src/{services,types}
cp storage/app/ts/services/*.ts frontend/src/services/
cp storage/app/ts/types/*.d.ts frontend/src/types/

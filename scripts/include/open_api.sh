Echo_Green '------------------------------'
Echo_Green ' 生成swagger接口文档'
Echo_Green '------------------------------'

vendor/bin/openapi app/Api/Auth \
  -o ../phpmall-docs/api/auth.json -f json

vendor/bin/openapi app/Api/Common \
  -o ../phpmall-docs/api/common.json -f json

vendor/bin/openapi app/Api/Manager \
  $(Get_Bundles "Admin") \
  -o ../phpmall-docs/api/admin.json -f json

vendor/bin/openapi app/Api/Seller \
  $(Get_Bundles "Seller") \
  -o ../phpmall-docs/api/seller.json -f json

vendor/bin/openapi app/Api/Supplier \
  $(Get_Bundles "Supplier") \
  -o ../phpmall-docs/api/supplier.json -f json

vendor/bin/openapi app/Api/User \
  $(Get_Bundles "User") \
  -o ../phpmall-docs/api/user.json -f json

vendor/bin/openapi app/Api/Portal \
  $(Get_Bundles "Portal") \
  -o ../phpmall-docs/api/portal.json -f json

Echo_Green '------------------------------'
Echo_Green ' 生成typescript接口'
Echo_Green '------------------------------'

php artisan gen:interface

cp storage/app/ts/services/auth.ts ../phpmall-mobile/src/services/auth.ts
cp storage/app/ts/services/common.ts ../phpmall-mobile/src/services/common.ts
cp storage/app/ts/services/portal.ts ../phpmall-mobile/src/services/portal.ts
cp storage/app/ts/services/user.ts ../phpmall-mobile/src/services/user.ts

cp storage/app/ts/types/auth.ts ../phpmall-mobile/src/types/auth.d.ts
cp storage/app/ts/types/common.ts ../phpmall-mobile/src/types/common.d.ts
cp storage/app/ts/types/portal.ts ../phpmall-mobile/src/types/portal.d.ts
cp storage/app/ts/types/user.ts ../phpmall-mobile/src/types/user.d.ts

rm -rf ../phpmall-web/src/services
rm -rf ../phpmall-web/src/types
cp -a storage/app/ts/* ../phpmall-web/src/

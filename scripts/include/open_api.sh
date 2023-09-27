Echo_Green '------------------------------'
Echo_Green ' 生成swagger接口文档'
Echo_Green '------------------------------'

vendor/bin/openapi app/Gateways/Auth \
  -o docs/api/auth.json -f json

vendor/bin/openapi app/Gateways/Common \
  -o docs/api/common.json -f json

vendor/bin/openapi app/Gateways/Manager \
  $(Get_Bundles "Admin") \
  -o docs/api/admin.json -f json

vendor/bin/openapi app/Gateways/Seller \
  $(Get_Bundles "Seller") \
  -o docs/api/seller.json -f json

vendor/bin/openapi app/Gateways/Supplier \
  $(Get_Bundles "Supplier") \
  -o docs/api/supplier.json -f json

vendor/bin/openapi app/Gateways/User \
  $(Get_Bundles "User") \
  -o docs/api/user.json -f json

vendor/bin/openapi app/Gateways/Portal \
  $(Get_Bundles "Portal") \
  -o docs/api/portal.json -f json

Echo_Green '------------------------------'
Echo_Green ' 生成typescript接口'
Echo_Green '------------------------------'

php artisan gen:interface

rm -rf */src/services
rm -rf */src/types
cp -a storage/app/ts/* desktop/src/
cp -a storage/app/ts/* mobile/src/

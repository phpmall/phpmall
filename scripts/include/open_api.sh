Echo_Green '------------------------------'
Echo_Green ' 生成swagger接口文档'
Echo_Green '------------------------------'

vendor/bin/openapi app/Gateways/Auth \
  -o public/swagger/auth.json -f json

vendor/bin/openapi app/Gateways/Manager \
  $(Get_Bundles "Admin") \
  -o public/swagger/admin.json -f json

vendor/bin/openapi app/Gateways/Seller \
  $(Get_Bundles "Seller") \
  -o public/swagger/seller.json -f json

vendor/bin/openapi app/Gateways/Supplier \
  $(Get_Bundles "Supplier") \
  -o public/swagger/supplier.json -f json

vendor/bin/openapi app/Gateways/User \
  $(Get_Bundles "User") \
  -o public/swagger/user.json -f json

vendor/bin/openapi app/Gateways/Portal \
  $(Get_Bundles "Portal") \
  -o public/swagger/portal.json -f json

Echo_Green '------------------------------'
Echo_Green ' 生成typescript接口'
Echo_Green '------------------------------'

php artisan gen:interface

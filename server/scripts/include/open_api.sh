Echo_Green '------------------------------'
Echo_Green ' 生成swagger接口文档'
Echo_Green '------------------------------'
vendor/bin/openapi app/Gateways/Admin/ -o public/swagger/admin.json -f json
vendor/bin/openapi app/Gateways/Auth/ -o public/swagger/auth.json -f json
vendor/bin/openapi app/Gateways/Portal/ -o public/swagger/portal.json -f json
vendor/bin/openapi app/Gateways/Seller/ -o public/swagger/seller.json -f json
vendor/bin/openapi app/Gateways/Supplier/ -o public/swagger/supplier.json -f json
vendor/bin/openapi app/Gateways/User/ -o public/swagger/user.json -f json

Echo_Green '------------------------------'
Echo_Green ' 生成typescript接口'
Echo_Green '------------------------------'
php artisan gen:interface

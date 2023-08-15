Echo_Green '------------------------------'
Echo_Green ' 生成swagger接口文档'
Echo_Green '------------------------------'
vendor/bin/openapi app/Gateways/Auth/ -o public/swagger/auth.yaml
vendor/bin/openapi app/Gateways/Manager/ -o public/swagger/manager.yaml
vendor/bin/openapi app/Gateways/Portal/ -o public/swagger/portal.yaml
vendor/bin/openapi app/Gateways/Seller/ -o public/swagger/seller.yaml
vendor/bin/openapi app/Gateways/Supplier/ -o public/swagger/supplier.yaml
vendor/bin/openapi app/Gateways/User/ -o public/swagger/user.yaml

Echo_Green '------------------------------'
Echo_Green ' 生成typescript接口'
Echo_Green '------------------------------'
vendor/bin/openapi app/Gateways/Auth/ -o storage/framework/cache/auth.json -f json
vendor/bin/openapi app/Gateways/Manager/ -o storage/framework/cache/manager.json -f json
vendor/bin/openapi app/Gateways/Portal/ -o storage/framework/cache/portal.json -f json
vendor/bin/openapi app/Gateways/Seller/ -o storage/framework/cache/seller.json -f json
vendor/bin/openapi app/Gateways/Supplier/ -o storage/framework/cache/supplier.json -f json
vendor/bin/openapi app/Gateways/User/ -o storage/framework/cache/user.json -f json

php artisan gen:interface

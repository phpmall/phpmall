#!/usr/bin/env bash

set -e

mkdir -p storage/openapi

vendor/bin/openapi app/Api/Admin app/Http/Responses app/Modules/*/Http -f json -o storage/openapi/Admin.json
vendor/bin/openapi app/Api/Common app/Http/Responses -f json -o storage/openapi/Common.json
vendor/bin/openapi app/Api/Portal app/Http/Responses -f json -o storage/openapi/Portal.json
vendor/bin/openapi app/Api/Seller app/Http/Responses -f json -o storage/openapi/Seller.json
vendor/bin/openapi app/Api/Shop app/Http/Responses -f json -o storage/openapi/Shop.json
vendor/bin/openapi app/Api/Supplier app/Http/Responses -f json -o storage/openapi/Supplier.json
vendor/bin/openapi app/Api/User app/Http/Responses -f json -o storage/openapi/User.json

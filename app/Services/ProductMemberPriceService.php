<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductMemberPriceRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class ProductMemberPriceService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductMemberPriceRepository
    {
        return ProductMemberPriceRepository::getInstance();
    }
}

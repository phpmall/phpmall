<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\ProductMemberPriceRepository;

class ProductMemberPriceService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductMemberPriceRepository
    {
        return ProductMemberPriceRepository::getInstance();
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Foundation\Contracts\ServiceInterface;
use App\Foundation\Services\CommonService;
use App\Repositories\ProductMemberPriceRepository;

class ProductMemberPriceService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductMemberPriceRepository
    {
        return ProductMemberPriceRepository::getInstance();
    }
}

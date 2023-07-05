<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductMemberPriceRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class ProductMemberPriceService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductMemberPriceRepository
    {
        return ProductMemberPriceRepository::getInstance();
    }
}

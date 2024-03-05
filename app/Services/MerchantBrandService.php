<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\MerchantBrandRepository;

class MerchantBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): MerchantBrandRepository
    {
        return MerchantBrandRepository::getInstance();
    }
}

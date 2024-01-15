<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\MerchantBrandRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class MerchantBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): MerchantBrandRepository
    {
        return MerchantBrandRepository::getInstance();
    }
}

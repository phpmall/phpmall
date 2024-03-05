<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\MerchantAddressRepository;

class MerchantAddressService extends CommonService implements ServiceInterface
{
    public function getRepository(): MerchantAddressRepository
    {
        return MerchantAddressRepository::getInstance();
    }
}

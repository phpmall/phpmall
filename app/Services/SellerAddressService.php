<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SellerAddressRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class SellerAddressService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerAddressRepository
    {
        return SellerAddressRepository::getInstance();
    }
}

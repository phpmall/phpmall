<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\SellerBrandRepository;

class SellerBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerBrandRepository
    {
        return SellerBrandRepository::getInstance();
    }
}

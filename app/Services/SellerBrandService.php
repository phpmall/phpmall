<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SellerBrandRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class SellerBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerBrandRepository
    {
        return SellerBrandRepository::getInstance();
    }
}

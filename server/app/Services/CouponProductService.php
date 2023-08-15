<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\CouponProductRepository;

class CouponProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponProductRepository
    {
        return CouponProductRepository::getInstance();
    }
}

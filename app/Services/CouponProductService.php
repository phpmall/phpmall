<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CouponProductRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class CouponProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponProductRepository
    {
        return CouponProductRepository::getInstance();
    }
}

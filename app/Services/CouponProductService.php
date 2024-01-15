<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CouponProductRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class CouponProductService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponProductRepository
    {
        return CouponProductRepository::getInstance();
    }
}

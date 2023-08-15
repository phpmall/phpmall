<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CouponRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class CouponService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponRepository
    {
        return CouponRepository::getInstance();
    }
}

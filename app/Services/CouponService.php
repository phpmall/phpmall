<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\CouponRepository;

class CouponService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponRepository
    {
        return CouponRepository::getInstance();
    }
}

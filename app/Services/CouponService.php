<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CouponRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class CouponService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponRepository
    {
        return CouponRepository::getInstance();
    }
}

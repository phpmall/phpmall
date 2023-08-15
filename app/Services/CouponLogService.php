<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\CouponLogRepository;

class CouponLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponLogRepository
    {
        return CouponLogRepository::getInstance();
    }
}

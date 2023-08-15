<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CouponLogRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class CouponLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponLogRepository
    {
        return CouponLogRepository::getInstance();
    }
}

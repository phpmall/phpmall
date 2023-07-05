<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CouponLogRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class CouponLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): CouponLogRepository
    {
        return CouponLogRepository::getInstance();
    }
}

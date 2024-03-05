<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\OrderLogRepository;

class OrderLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderLogRepository
    {
        return OrderLogRepository::getInstance();
    }
}

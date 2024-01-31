<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderLogRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class OrderLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderLogRepository
    {
        return OrderLogRepository::getInstance();
    }
}

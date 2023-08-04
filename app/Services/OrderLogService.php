<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderLogRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class OrderLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderLogRepository
    {
        return OrderLogRepository::getInstance();
    }
}

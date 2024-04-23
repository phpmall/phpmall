<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SystemEmployeeLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemEmployeeLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemEmployeeLogRepository
    {
        return SystemEmployeeLogRepository::getInstance();
    }
}

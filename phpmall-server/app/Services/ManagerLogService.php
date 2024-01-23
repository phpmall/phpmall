<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ManagerLogRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ManagerLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): ManagerLogRepository
    {
        return ManagerLogRepository::getInstance();
    }
}

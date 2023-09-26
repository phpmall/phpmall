<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AdminLogRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class AdminLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminLogRepository
    {
        return AdminLogRepository::getInstance();
    }
}

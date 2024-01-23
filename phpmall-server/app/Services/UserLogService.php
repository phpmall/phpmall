<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserLogRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class UserLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserLogRepository
    {
        return UserLogRepository::getInstance();
    }
}

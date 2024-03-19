<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserLogRepository
    {
        return UserLogRepository::getInstance();
    }
}

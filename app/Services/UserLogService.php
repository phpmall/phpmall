<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserLogRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class UserLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserLogRepository
    {
        return UserLogRepository::getInstance();
    }
}

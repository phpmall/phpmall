<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Repositories\UserAccountLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserAccountLogBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserAccountLogRepository
    {
        return UserAccountLogRepository::getInstance();
    }
}

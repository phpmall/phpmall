<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Repositories\UserRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserRepository
    {
        return UserRepository::getInstance();
    }
}

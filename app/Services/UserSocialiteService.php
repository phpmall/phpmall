<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\UserSocialiteRepository;

class UserSocialiteService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserSocialiteRepository
    {
        return UserSocialiteRepository::getInstance();
    }
}

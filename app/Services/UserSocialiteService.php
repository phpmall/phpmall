<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserSocialiteRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class UserSocialiteService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserSocialiteRepository
    {
        return UserSocialiteRepository::getInstance();
    }
}

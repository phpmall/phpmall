<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserSocialiteRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class UserSocialiteService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserSocialiteRepository
    {
        return UserSocialiteRepository::getInstance();
    }
}

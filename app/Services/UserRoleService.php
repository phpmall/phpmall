<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\UserRoleRepository;

class UserRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserRoleRepository
    {
        return UserRoleRepository::getInstance();
    }
}

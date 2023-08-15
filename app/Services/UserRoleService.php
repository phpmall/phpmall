<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRoleRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class UserRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserRoleRepository
    {
        return UserRoleRepository::getInstance();
    }
}

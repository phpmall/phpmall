<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRoleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserRoleRepository
    {
        return UserRoleRepository::getInstance();
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\ManagerRoleRepository;

class ManagerRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ManagerRoleRepository
    {
        return ManagerRoleRepository::getInstance();
    }
}

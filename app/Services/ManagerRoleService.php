<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ManagerRoleRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ManagerRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ManagerRoleRepository
    {
        return ManagerRoleRepository::getInstance();
    }
}

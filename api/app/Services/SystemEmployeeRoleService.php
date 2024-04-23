<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SystemEmployeeRoleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemEmployeeRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemEmployeeRoleRepository
    {
        return SystemEmployeeRoleRepository::getInstance();
    }
}

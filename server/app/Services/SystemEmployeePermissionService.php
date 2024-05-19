<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SystemEmployeePermissionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemEmployeePermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemEmployeePermissionRepository
    {
        return SystemEmployeePermissionRepository::getInstance();
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RolePermissionRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class RolePermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): RolePermissionRepository
    {
        return RolePermissionRepository::getInstance();
    }
}

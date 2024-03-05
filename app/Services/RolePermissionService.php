<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\RolePermissionRepository;

class RolePermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): RolePermissionRepository
    {
        return RolePermissionRepository::getInstance();
    }
}

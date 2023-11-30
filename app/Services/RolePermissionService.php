<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RolePermissionRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class RolePermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): RolePermissionRepository
    {
        return RolePermissionRepository::getInstance();
    }
}

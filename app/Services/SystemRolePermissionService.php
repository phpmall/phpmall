<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SystemRolePermissionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemRolePermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemRolePermissionRepository
    {
        return SystemRolePermissionRepository::getInstance();
    }
}

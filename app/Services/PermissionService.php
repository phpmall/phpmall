<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\PermissionRepository;

class PermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): PermissionRepository
    {
        return PermissionRepository::getInstance();
    }
}

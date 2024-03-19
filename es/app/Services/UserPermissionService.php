<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserPermissionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserPermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): UserPermissionRepository
    {
        return UserPermissionRepository::getInstance();
    }
}

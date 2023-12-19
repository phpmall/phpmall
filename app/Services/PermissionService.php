<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PermissionRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class PermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): PermissionRepository
    {
        return PermissionRepository::getInstance();
    }
}

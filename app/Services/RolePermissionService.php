<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RolePermissionRepository;
use App\Services\Input\RolePermissionInput;
use App\Services\Output\RolePermissionOutput;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class RolePermissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): RolePermissionRepository
    {
        return RolePermissionRepository::getInstance();
    }
}

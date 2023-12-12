<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AdminRoleRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class AdminRoleService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminRoleRepository
    {
        return AdminRoleRepository::getInstance();
    }
}

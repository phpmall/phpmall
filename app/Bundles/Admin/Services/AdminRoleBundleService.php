<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Services;

use App\Bundles\Admin\Repositories\AdminRoleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdminRoleBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminRoleRepository
    {
        return AdminRoleRepository::getInstance();
    }
}

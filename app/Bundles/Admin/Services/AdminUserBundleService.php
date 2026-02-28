<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Services;

use App\Bundles\Admin\Repositories\AdminUserRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdminUserBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminUserRepository
    {
        return AdminUserRepository::getInstance();
    }
}

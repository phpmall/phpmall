<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Services;

use App\Bundles\Admin\Repositories\AdminLogRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdminLogBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdminLogRepository
    {
        return AdminLogRepository::getInstance();
    }
}

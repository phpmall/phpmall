<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Services;

use App\Bundles\Activity\Repositories\ActivityPackageRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ActivityPackageBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ActivityPackageRepository
    {
        return ActivityPackageRepository::getInstance();
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Services;

use App\Bundles\Activity\Repositories\ActivityRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ActivityBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ActivityRepository
    {
        return ActivityRepository::getInstance();
    }
}

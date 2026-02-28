<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Services;

use App\Bundles\Activity\Repositories\ActivityGroupRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ActivityGroupBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ActivityGroupRepository
    {
        return ActivityGroupRepository::getInstance();
    }
}

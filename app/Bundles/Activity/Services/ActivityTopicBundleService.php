<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Services;

use App\Bundles\Activity\Repositories\ActivityTopicRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ActivityTopicBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ActivityTopicRepository
    {
        return ActivityTopicRepository::getInstance();
    }
}

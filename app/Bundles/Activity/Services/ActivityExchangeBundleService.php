<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Services;

use App\Bundles\Activity\Repositories\ActivityExchangeRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ActivityExchangeBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ActivityExchangeRepository
    {
        return ActivityExchangeRepository::getInstance();
    }
}

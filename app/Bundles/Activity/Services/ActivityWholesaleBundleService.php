<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Services;

use App\Bundles\Activity\Repositories\ActivityWholesaleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ActivityWholesaleBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ActivityWholesaleRepository
    {
        return ActivityWholesaleRepository::getInstance();
    }
}

<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Services;

use App\Bundles\Activity\Repositories\ActivityAuctionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ActivityAuctionBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ActivityAuctionRepository
    {
        return ActivityAuctionRepository::getInstance();
    }
}

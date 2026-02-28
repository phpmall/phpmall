<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Services;

use App\Bundles\Ad\Repositories\AdAdsenseRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdAdsenseBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdAdsenseRepository
    {
        return AdAdsenseRepository::getInstance();
    }
}

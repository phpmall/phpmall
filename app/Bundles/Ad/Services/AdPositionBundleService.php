<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Services;

use App\Bundles\Ad\Repositories\AdPositionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdPositionBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): AdPositionRepository
    {
        return AdPositionRepository::getInstance();
    }
}

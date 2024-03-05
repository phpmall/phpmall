<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\RegionRepository;

class RegionService extends CommonService implements ServiceInterface
{
    public function getRepository(): RegionRepository
    {
        return RegionRepository::getInstance();
    }
}

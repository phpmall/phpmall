<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RegionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class RegionService extends CommonService implements ServiceInterface
{
    public function getRepository(): RegionRepository
    {
        return RegionRepository::getInstance();
    }
}
